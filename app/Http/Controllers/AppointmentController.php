<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ==================== INDEX (POUR SECRÉTAIRE/ADMIN) ====================
    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('date_time', 'desc')
            ->get();
        return view('appointments.index', compact('appointments'));
    }

    // ==================== CREATE (POUR SECRÉTAIRE) ====================
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    // ==================== STORE (CRÉER RENDEZ-VOUS PAR SECRÉTAIRE) ====================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date_time' => 'required|date|after:now',
            'duration' => 'nullable|integer|min:15|max:120',
            'type' => 'nullable|string|in:general,follow-up,emergency,consultation',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        // Vérifier disponibilité du créneau
        if (!Appointment::isTimeSlotAvailable($validated['doctor_id'], $validated['date_time'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Ce créneau n\'est plus disponible. Veuillez choisir un autre horaire.');
        }

        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'date_time' => $validated['date_time'],
            'duration' => $validated['duration'] ?? 30,
            'status' => 'pending',
            'type' => $validated['type'] ?? 'general',
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $appointment->load(['patient.user', 'doctor.user']);
        
        $patientUser = $appointment->patient->user;
        $doctorUser = $appointment->doctor->user;
        $appointmentDate = Carbon::parse($validated['date_time']);

        // 🔔 Notifier le PATIENT
        try {
            if ($patientUser) {
                Mail::send('emails.appointment-pending', [
                    'patientName' => $patientUser->name,
                    'doctorName' => $doctorUser?->name ?? 'Médecin',
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'appointmentId' => $appointment->id,
                    'reason' => $validated['reason'] ?? 'Consultation générale'
                ], function ($message) use ($patientUser) {
                    $message->to($patientUser->email)
                            ->subject('⏳ Demande de rendez-vous en attente - HealthSys');
                });
            }
        } catch (\Exception $e) {
            Log::warning('Patient pending email failed: ' . $e->getMessage());
        }

        // 🔔 Notifier le MÉDECIN
        try {
            if ($doctorUser) {
                $doctorUser->notify(new SystemNotification('appointment.new', [
                    'name' => $doctorUser->name ?? 'Médecin',
                    'patient_name' => $patientUser?->name ?? 'Patient',
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'reason' => $validated['reason'] ?? 'Consultation',
                    'type' => $validated['type'] ?? 'general',
                    'id' => $appointment->id,
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Doctor notification failed: ' . $e->getMessage());
        }

        // 🔔 Notifier les SECRÉTAIRES
        try {
            $secretaries = User::where('role', 'secretaire')
                ->where('departement_id', $doctorUser?->departement_id ?? null)
                ->get();
            
            if ($secretaries->isEmpty()) {
                $secretaries = User::where('role', 'secretaire')->get();
            }
            
            foreach ($secretaries as $secretary) {
                $secretary->notify(new SystemNotification('appointment.new_for_secretary', [
                    'name' => $secretary->name,
                    'patient_name' => $patientUser?->name ?? 'Patient',
                    'doctor_name' => $doctorUser?->name ?? 'Médecin',
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'id' => $appointment->id,
                ]));
                
                Mail::send('emails.secretary-pending-appointment', [
                    'secretaryName' => $secretary->name,
                    'patientName' => $patientUser?->name ?? 'Patient',
                    'doctorName' => $doctorUser?->name ?? 'Médecin',
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'appointmentId' => $appointment->id,
                    'reason' => $validated['reason'] ?? 'Consultation générale'
                ], function ($message) use ($secretary) {
                    $message->to($secretary->email)
                            ->subject('📅 Nouveau rendez-vous à confirmer - HealthSys');
                });
            }
        } catch (\Exception $e) {
            Log::warning('Secretary notification failed: ' . $e->getMessage());
        }

        return redirect()->to('/secretaire/appointments')
            ->with('success', '✅ Rendez-vous créé ! La secrétaire va le confirmer.');
    }

    // ==================== CONFIRMER RENDEZ-VOUS PAR SECRÉTAIRE ====================
    public function confirm($id)
    {
        $appointment = Appointment::with(['patient.user', 'doctor.user'])->findOrFail($id);
        
        // Vérifier que le créneau est toujours disponible
        if (!Appointment::isTimeSlotAvailable($appointment->doctor_id, $appointment->date_time, $appointment->id)) {
            return redirect()->back()->with('error', '❌ Ce créneau n\'est plus disponible.');
        }
        
        // Mettre à jour le statut
        $appointment->status = 'confirmed';
        $appointment->confirmed_by = auth()->id();
        $appointment->confirmed_at = now();
        $appointment->save();
        
        $patientUser = $appointment->patient->user;
        $doctorUser = $appointment->doctor->user;
        $appointmentDate = Carbon::parse($appointment->date_time);
        
        // 📧 EMAIL DE CONFIRMATION AU PATIENT
        try {
            if ($patientUser) {
                Mail::send('emails.appointment-confirmed', [
                    'patientName' => $patientUser->name,
                    'doctorName' => $doctorUser?->name ?? 'Médecin',
                    'doctorSpecialty' => $appointment->doctor->specialty ?? 'Généraliste',
                    'doctorPhone' => $doctorUser?->phone ?? '',
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'appointmentId' => $appointment->id,
                    'reason' => $appointment->reason ?? 'Consultation générale'
                ], function ($message) use ($patientUser) {
                    $message->to($patientUser->email)
                            ->subject('✅ Votre rendez-vous est confirmé - HealthSys');
                });
            }
        } catch (\Exception $e) {
            Log::warning('Confirmation email failed: ' . $e->getMessage());
        }
        
        // 🔔 NOTIFICATION AU PATIENT (base de données)
        try {
            if ($patientUser) {
                $patientUser->notify(new SystemNotification('appointment.confirmed_for_patient', [
                    'name' => $patientUser->name,
                    'doctor_name' => $doctorUser?->name ?? 'Médecin',
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'id' => $appointment->id,
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Patient DB notification failed: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', '✅ Rendez-vous confirmé ! Le patient a reçu un email de confirmation.');
    }

    // ==================== SHOW (POUR SECRÉTAIRE) ====================
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user']);
        
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $appointment->id,
                'patient' => ['user' => ['name' => $appointment->patient->user->name, 'phone' => $appointment->patient->user->phone]],
                'doctor' => ['user' => ['name' => $appointment->doctor->user->name], 'specialty' => $appointment->doctor->specialty],
                'date_time' => $appointment->date_time,
                'status' => $appointment->status,
                'reason' => $appointment->reason,
                'notes' => $appointment->notes,
            ]);
        }
        
        return view('appointments.show', compact('appointment'));
    }

    // ==================== EDIT ====================
    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    // ==================== UPDATE ====================
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date_time' => 'required|date',
            'status' => 'required',
            'type' => 'required',
        ]);

        $appointment->update([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date_time' => $request->date_time,
            'duration' => $request->duration ?? 30,
            'type' => $request->type,
            'status' => $request->status,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);

        return redirect()->to('/secretaire/appointments')
            ->with('success', 'Rendez-vous modifié avec succès');
    }

    // ==================== DESTROY ====================
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->to('/secretaire/appointments')
            ->with('success', 'Rendez-vous supprimé avec succès');
    }

    // ==================== PATIENT INDEX ====================
    public function patientIndex()
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            $patient = Patient::create(['user_id' => $user->id]);
            $user->refresh();
            $patient = $user->patient;
        }
        
        $appointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('date_time', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json($appointments);
        }

        $doctors = Doctor::with('user')->get();
        return view('patient.appointments', compact('appointments', 'doctors'));
    }

    // ==================== PATIENT SHOW ====================
    public function patientShow($id)
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Patient non trouvé'], 404);
            }
            abort(404, 'Patient non trouvé');
        }
        
        $appointment = Appointment::with(['doctor.user'])
            ->where('id', $id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'id' => $appointment->id,
                'date_time' => $appointment->date_time,
                'date' => $appointment->date_time->format('d/m/Y'),
                'time' => $appointment->date_time->format('H:i'),
                'doctor_name' => $appointment->doctor->user->name,
                'doctor_specialty' => $appointment->doctor->specialty,
                'status' => $appointment->status,
                'reason' => $appointment->reason,
                'type' => $appointment->type,
                'duration' => $appointment->duration,
            ]);
        }
        
        return view('patient.appointment-show', compact('appointment'));
    }

    // ==================== PATIENT CONFIRM ONLINE ====================
    public function confirmOnline($id)
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Patient non trouvé']);
            }
            return redirect()->route('patient.appointments')->with('error', 'Patient non trouvé');
        }
        
        $appointment = Appointment::where('id', $id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();
        
        if ($appointment->status == 'confirmed') {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Rendez-vous déjà confirmé']);
            }
            return redirect()->route('patient.appointments')->with('error', 'Rendez-vous déjà confirmé');
        }
        
        $appointment->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        
        // Notifier le médecin
        try {
            $appointment->doctor->user->notify(new SystemNotification('appointment.confirmed_by_patient', [
                'name' => $appointment->doctor->user->name,
                'patient_name' => $patient->user->name,
                'doctor_name' => $appointment->doctor->user->name,
                'date' => $appointment->date_time->format('d/m/Y'),
                'time' => $appointment->date_time->format('H:i'),
                'id' => $appointment->id,
            ]));
        } catch (\Exception $e) {
            Log::warning('Doctor confirmation notification failed: ' . $e->getMessage());
        }
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rendez-vous confirmé avec succès']);
        }
        
        return redirect()->route('patient.appointments')->with('success', 'Rendez-vous confirmé avec succès');
    }

    // ==================== PATIENT BOOK ONLINE ====================
    public function bookOnline(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            $patient = Patient::create(['user_id' => $user->id]);
            $user->refresh();
            $patient = $user->patient;
        }
        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:now',
            'reason' => 'nullable|string|max:500',
        ]);

        $dateTime = Carbon::parse($request->date);
        
        if ($dateTime->hour === 0 && $dateTime->minute === 0) {
            $dateTime = $dateTime->setTime(9, 0);
        }

        // Vérifier disponibilité
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date_time', $dateTime)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false, 
                'message' => 'Ce créneau n\'est pas disponible. Veuillez choisir un autre horaire.'
            ], 409);
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'date_time' => $dateTime,
            'reason' => $request->reason,
            'status' => 'pending',
            'type' => 'general',
            'duration' => 30,
        ]);

        $appointment->load(['patient.user', 'doctor.user']);
        
        $patientUser = $appointment->patient->user;
        $doctorUser = $appointment->doctor->user;

        // 📧 Email au patient (demande en attente)
        try {
            if ($patientUser) {
                Mail::send('emails.appointment-pending', [
                    'patientName' => $patientUser->name,
                    'doctorName' => $doctorUser?->name ?? 'Médecin',
                    'date' => $dateTime->format('d/m/Y'),
                    'time' => $dateTime->format('H:i'),
                    'appointmentId' => $appointment->id,
                    'reason' => $request->reason ?? 'Consultation générale'
                ], function ($message) use ($patientUser) {
                    $message->to($patientUser->email)
                            ->subject('⏳ Votre demande de rendez-vous est enregistrée - HealthSys');
                });
            }
        } catch (\Exception $e) {
            Log::warning('Patient pending email failed: ' . $e->getMessage());
        }

        // 🔔 Notifier le MÉDECIN
        try {
            if ($doctorUser) {
                $doctorUser->notify(new SystemNotification('appointment.new', [
                    'name' => $doctorUser->name ?? 'Médecin',
                    'patient_name' => $patientUser?->name ?? 'Patient',
                    'date' => $dateTime->format('d/m/Y'),
                    'time' => $dateTime->format('H:i'),
                    'reason' => $request->reason ?? 'Consultation',
                    'type' => 'general',
                    'id' => $appointment->id,
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Doctor notification failed: ' . $e->getMessage());
        }

        // 🔔 Notifier les SECRÉTAIRES
        try {
            $secretaries = User::where('role', 'secretaire')
                ->where('departement_id', $doctorUser?->departement_id ?? null)
                ->get();
            
            if ($secretaries->isEmpty()) {
                $secretaries = User::where('role', 'secretaire')->get();
            }
            
            foreach ($secretaries as $secretary) {
                $secretary->notify(new SystemNotification('appointment.new_for_secretary', [
                    'name' => $secretary->name,
                    'patient_name' => $patientUser?->name ?? 'Patient',
                    'doctor_name' => $doctorUser?->name ?? 'Médecin',
                    'date' => $dateTime->format('d/m/Y'),
                    'time' => $dateTime->format('H:i'),
                    'id' => $appointment->id,
                ]));
                
                Mail::send('emails.secretary-pending-appointment', [
                    'secretaryName' => $secretary->name,
                    'patientName' => $patientUser?->name ?? 'Patient',
                    'doctorName' => $doctorUser?->name ?? 'Médecin',
                    'date' => $dateTime->format('d/m/Y'),
                    'time' => $dateTime->format('H:i'),
                    'appointmentId' => $appointment->id,
                    'reason' => $request->reason ?? 'Consultation générale'
                ], function ($message) use ($secretary) {
                    $message->to($secretary->email)
                            ->subject('📅 Nouveau rendez-vous à confirmer - HealthSys');
                });
            }
        } catch (\Exception $e) {
            Log::warning('Secretary notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true, 
            'appointment' => $appointment,
            'message' => 'Demande de rendez-vous envoyée ! La secrétaire va la confirmer.'
        ]);
    }

    // ==================== PATIENT CANCEL ONLINE ====================
    public function cancelOnline($id)
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            return response()->json(['success' => false, 'message' => 'Patient non trouvé']);
        }
        
        $appointment = Appointment::where('id', $id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        $oldStatus = $appointment->status;
        $appointment->update(['status' => 'cancelled', 'cancelled_at' => now()]);
        
        // Notifier le médecin
        try {
            $appointment->doctor->user->notify(new SystemNotification('appointment.cancellation', [
                'name' => $appointment->doctor->user->name,
                'patient_name' => $patient->user->name,
                'date' => $appointment->date_time->format('d/m/Y'),
                'time' => $appointment->date_time->format('H:i'),
                'doctor' => $appointment->doctor->user->name,
                'id' => $appointment->id,
            ]));
        } catch (\Exception $e) {
            Log::warning('Doctor cancellation notification failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }

    // ==================== DOCTOR SHOW ====================
    public function doctorShow($id)
    {
        $user = auth()->user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Médecin non trouvé'], 404);
            }
            abort(404, 'Médecin non trouvé');
        }
        
        $appointment = Appointment::with(['patient.user'])
            ->where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->user->name,
                'patient_phone' => $appointment->patient->user->phone,
                'patient_email' => $appointment->patient->user->email,
                'date' => $appointment->date_time->format('d/m/Y'),
                'time' => $appointment->date_time->format('H:i'),
                'status' => $appointment->status,
                'reason' => $appointment->reason,
                'type' => $appointment->type,
            ]);
        }
        
        return view('patient.appointment-show', ['appointment' => $appointment]);
    }
    // ==================== ANNULER RENDEZ-VOUS PAR SECRÉTAIRE ====================
public function cancelBySecretary($id)
{
    $appointment = Appointment::with(['patient.user', 'doctor.user'])->findOrFail($id);
    
    // Sauvegarder l'ancien statut
    $oldStatus = $appointment->status;
    
    // Mettre à jour le statut
    $appointment->status = 'cancelled';
    $appointment->cancelled_by = auth()->id();
    $appointment->cancelled_at = now();
    $appointment->cancellation_reason = request()->reason ?? 'Annulé par le secrétariat';
    $appointment->save();
    
    $patientUser = $appointment->patient->user;
    $doctorUser = $appointment->doctor->user;
    $appointmentDate = Carbon::parse($appointment->date_time);
    $cancellationReason = request()->reason ?? 'Annulation par le secrétariat';
    
    // 📧 EMAIL D'ANNULATION AU PATIENT
    try {
        if ($patientUser) {
            Mail::send('emails.appointment-cancelled', [
                'patientName' => $patientUser->name,
                'doctorName' => $doctorUser?->name ?? 'Médecin',
                'date' => $appointmentDate->format('d/m/Y'),
                'time' => $appointmentDate->format('H:i'),
                'reason' => $cancellationReason,
                'appointmentId' => $appointment->id
            ], function ($message) use ($patientUser) {
                $message->to($patientUser->email)
                        ->subject('❌ Annulation de rendez-vous - HealthSys');
            });
        }
    } catch (\Exception $e) {
        Log::warning('Cancellation email failed: ' . $e->getMessage());
    }
    
    // 🔔 NOTIFICATION AU PATIENT (base de données)
    try {
        if ($patientUser) {
            $patientUser->notify(new SystemNotification('appointment.cancellation', [
                'name' => $patientUser->name,
                'doctor' => $doctorUser?->name ?? 'Médecin',
                'date' => $appointmentDate->format('d/m/Y'),
                'time' => $appointmentDate->format('H:i'),
                'reason' => $cancellationReason,
                'id' => $appointment->id,
            ]));
        }
    } catch (\Exception $e) {
        Log::warning('Patient cancellation notification failed: ' . $e->getMessage());
    }
    
    // 🔔 NOTIFICATION AU MÉDECIN
    try {
        if ($doctorUser) {
            $doctorUser->notify(new SystemNotification('appointment.cancellation', [
                'name' => $doctorUser->name,
                'patient_name' => $patientUser?->name ?? 'Patient',
                'date' => $appointmentDate->format('d/m/Y'),
                'time' => $appointmentDate->format('H:i'),
                'doctor' => $doctorUser->name,
                'id' => $appointment->id,
            ]));
        }
    } catch (\Exception $e) {
        Log::warning('Doctor cancellation notification failed: ' . $e->getMessage());
    }
    
    return redirect()->back()->with('success', '❌ Rendez-vous annulé ! Le patient a été notifié par email.');
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('date_time', 'desc')
            ->get();
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        // 1. ✅ Validate input
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date_time' => 'required|date|after:now',
            'duration' => 'nullable|integer|min:15|max:120',
            'type' => 'nullable|string|in:general,follow-up,emergency,consultation',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        // 2. ✅ Create the appointment
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

        // 3. ✅ Load relationships for notification data
        $appointment->load(['patient.user', 'doctor.user']);
        
        $patientUser = $appointment->patient->user;
        $doctorUser = $appointment->doctor->user;
        $appointmentDate = Carbon::parse($validated['date_time']);

        // 4. 🎉 Send notification to PATIENT
        try {
            if ($patientUser) {
                $patientUser->notify(new SystemNotification('appointment.confirmation', [
                    'name' => $patientUser->name ?? 'Patient',
                    'email' => $patientUser->email,
                    'date' => $appointmentDate->format('d/m/Y'),
                    'time' => $appointmentDate->format('H:i'),
                    'doctor' => $doctorUser?->name ?? 'Non spécifié',
                    'id' => $appointment->id,
                    'type' => $validated['type'] ?? 'Consultation',
                    'reason' => $validated['reason'] ?? 'Consultation générale',
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Patient notification failed: ' . $e->getMessage(), [
                'patient_id' => $validated['patient_id'],
                'appointment_id' => $appointment->id
            ]);
        }

        // 5. 🩺 Send notification to DOCTOR
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
            Log::warning('Doctor notification failed: ' . $e->getMessage(), [
                'doctor_id' => $validated['doctor_id'],
                'appointment_id' => $appointment->id
            ]);
        }

        // 6. ✅ Redirect with success message
        return redirect()->to('/secretaire/appointments')
            ->with('success', '✅ Rendez-vous créé ! Notifications envoyées au patient et au médecin.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

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

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->to('/secretaire/appointments')
            ->with('success', 'Rendez-vous supprimé avec succès');
    }

    // ========== MÉTHODES POUR LE PATIENT ==========

    public function patientIndex()
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            $patient = Patient::create([
                'user_id' => $user->id,
            ]);
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

    public function bookOnline(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        if (!$patient) {
            $patient = Patient::create([
                'user_id' => $user->id,
            ]);
            $user->refresh();
            $patient = $user->patient;
        }
        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:now',
            'reason' => 'nullable|string|max:500',
        ]);

        // Parse date/time
        $dateTime = Carbon::parse($request->date);
        
        // If no time specified (only date), default to 9:00 AM
        if ($dateTime->hour === 0 && $dateTime->minute === 0) {
            $dateTime = $dateTime->setTime(9, 0);
        }

        // Check availability
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

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'date_time' => $dateTime,
            'reason' => $request->reason,
            'status' => 'pending',
            'type' => 'general',
            'duration' => 30,
        ]);

        // 🎉 Load relationships
        $appointment->load(['patient.user', 'doctor.user']);
        
        $patientUser = $appointment->patient->user;
        $doctorUser = $appointment->doctor->user;

        // 📧 Send notification to PATIENT
        try {
            if ($patientUser) {
                $patientUser->notify(new SystemNotification('appointment.confirmation', [
                    'name' => $patientUser->name ?? 'Patient',
                    'email' => $patientUser->email,
                    'date' => $dateTime->format('d/m/Y'),
                    'time' => $dateTime->format('H:i'),
                    'doctor' => $doctorUser?->name ?? 'Non spécifié',
                    'id' => $appointment->id,
                    'type' => 'Consultation',
                    'reason' => $request->reason ?? 'Consultation générale',
                ]));
            }
        } catch (\Exception $e) {
            Log::warning('Patient notification failed: ' . $e->getMessage());
        }

        // 🩺 Send notification to DOCTOR
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

        return response()->json([
            'success' => true, 
            'appointment' => $appointment,
            'message' => 'Rendez-vous créé ! Notifications envoyées.'
        ]);
    }

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

        $appointment->update(['status' => 'cancelled']);

        return response()->json(['success' => true]);
    }
}
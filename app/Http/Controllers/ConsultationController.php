<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $consultations = Consultation::with(['patient.user', 'doctor.user'])
            ->orderBy('consultation_date', 'desc')
            ->get();
        return view('consultations.index', compact('consultations'));
    }

    // ========== MÉTHODES POUR LE SECRÉTAIRE / ADMIN ==========
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'confirmed')
            ->where('date_time', '<', now())
            ->get();
        return view('consultations.create', compact('patients', 'doctors', 'appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'consultation_date' => 'required|date',
        ]);

        $consultation = Consultation::create([
            'appointment_id' => $request->appointment_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'consultation_date' => $request->consultation_date,
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'weight' => $request->weight,
            'height' => $request->height,
            'blood_pressure' => $request->blood_pressure,
            'temperature' => $request->temperature,
            'heart_rate' => $request->heart_rate,
            'notes' => $request->notes,
        ]);

        if ($request->appointment_id) {
            Appointment::where('id', $request->appointment_id)->update(['status' => 'completed']);
        }

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation enregistrée avec succès');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient.user', 'doctor.user', 'appointment']);
        return view('consultations.show', compact('consultation'));
    }

    public function edit(Consultation $consultation)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('consultations.edit', compact('consultation', 'patients', 'doctors'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'consultation_date' => 'required|date',
        ]);

        $consultation->update($request->all());

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation modifiée avec succès');
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();
        return redirect()->route('consultations.index')
            ->with('success', 'Consultation supprimée avec succès');
    }

    // ========== MÉTHODES POUR LE MÉDECIN ==========
    public function doctorConsultations()
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            abort(403, 'Vous n\'êtes pas associé à un profil médecin.');
        }
        
        $consultations = Consultation::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('consultation_date', 'desc')
            ->get();
        
        return view('doctor.consultations', compact('consultations'));
    }

    public function visitHistory()
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            abort(403, 'Vous n\'êtes pas associé à un profil médecin.');
        }
        
        $consultations = Consultation::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('consultation_date', 'desc')
            ->get();
        
        return view('doctor.history', compact('consultations'));
    }

    // ========== MÉTHODES POUR LE PATIENT ==========
    public function patientMedicalRecord()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            abort(403, 'Vous n\'êtes pas associé à un profil patient.');
        }
        
        $consultations = Consultation::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('consultation_date', 'desc')
            ->get();
        
        return view('patient.medical-record', compact('consultations'));
    }

    public function details(Consultation $consultation)
    {
        $consultation->load(['patient.user', 'doctor.user']);
        return response()->json($consultation);
    }

    // ========== MÉTHODES POUR CRÉER CONSULTATION DEPUIS DASHBOARD MÉDECIN ==========
    public function createConsultation(Request $request)
    {
        $patientId = $request->query('patient');
        $appointmentId = $request->query('appointment');
        $waitingId = $request->query('waiting_id');
        
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            abort(403, 'Profil médecin non trouvé.');
        }

        $patient = null;
        if ($patientId) {
            $patient = Patient::with('user')->findOrFail($patientId);
        }

        $appointment = null;
        if ($appointmentId && $appointmentId > 0) {
            $appointment = Appointment::find($appointmentId);
            // Vérifier que le rendez-vous appartient bien au médecin connecté
            if ($appointment && $appointment->doctor_id != $doctor->id) {
                $appointment = null;
            }
        }

        // Si aucun patient n'est sélectionné, on récupère la liste des patients du médecin
        $patients = [];
        if (!$patient) {
            $patients = Patient::with('user')->get();
        }
        
        return view('doctor.consultation-create', compact('patient', 'patients', 'appointment', 'waitingId'));
    }

    public function storeConsultation(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'waiting_id' => 'nullable|exists:waiting_rooms,id',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'heart_rate' => 'nullable|string',
        ]);

        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->back()->with('error', 'Profil médecin non trouvé.');
        }

        // Vérifier que le rendez-vous appartient au médecin connecté si fourni
        if ($request->appointment_id) {
            $appointment = Appointment::findOrFail($request->appointment_id);
            if ($appointment->doctor_id != $doctor->id) {
                abort(403, 'Accès non autorisé');
            }
            // Mettre à jour le statut du rendez-vous
            $appointment->update(['status' => 'completed']);
        }

        $consultation = Consultation::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $request->appointment_id,
            'consultation_date' => now(),
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'notes' => $request->notes,
            'weight' => $request->weight,
            'height' => $request->height,
            'blood_pressure' => $request->blood_pressure,
            'temperature' => $request->temperature,
            'heart_rate' => $request->heart_rate,
        ]);

        // Si vient de la salle d'attente, marquer comme terminé
        if ($request->waiting_id) {
            $waiting = \App\Models\WaitingRoom::find($request->waiting_id);
            if ($waiting) {
                $waiting->update([
                    'status' => 'completed',
                    'end_time' => now()
                ]);
            }
        }

        return redirect()->route('doctor.consultations')
            ->with('success', 'Consultation enregistrée avec succès');
    }
    
    public function doctorShowConsultation($id)
    {
        $consultation = Consultation::with(['patient.user', 'appointment'])
            ->where('doctor_id', Auth::user()->doctor->id)
            ->findOrFail($id);
        
        return view('doctor.consultation-show', compact('consultation'));
    }
}
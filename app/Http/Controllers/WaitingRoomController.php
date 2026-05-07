<?php

namespace App\Http\Controllers;

use App\Models\WaitingRoom;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;

class WaitingRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Pour la secrétaire
    public function secretaireIndex()
    {
        $waiting = WaitingRoom::with(['patient.user', 'doctor.user'])
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('arrival_time', 'asc')
            ->get();

        // إضافة هذا المتغير ✅
        $inConsultation = WaitingRoom::with(['patient.user', 'doctor.user'])
            ->where('status', 'in_consultation')
            ->first();

        $doctors = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();
        
        // جلب rendez-vous pour les associer (اختياري)
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'confirmed')
            ->where('date_time', '>', now())
            ->orderBy('date_time', 'asc')
            ->get();

        return view('secretaire.waiting-room', compact('waiting', 'doctors', 'patients', 'inConsultation', 'appointments'));
    }

    // Pour le médecin
    public function doctorIndex()
    {
        $doctorId = auth()->user()->doctor->id;
        
        $waiting = WaitingRoom::with(['patient.user', 'doctor.user'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('arrival_time', 'asc')
            ->get();

        $inConsultation = WaitingRoom::with(['patient.user', 'doctor.user'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'in_consultation')
            ->first();

        return view('doctor.waiting-room', compact('waiting', 'inConsultation'));
    }

    // Ajouter un patient (secrétaire)
    public function add(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'priority' => 'nullable|integer|min:0|max:2',
        ]);

        $existing = WaitingRoom::where('patient_id', $request->patient_id)
            ->whereIn('status', ['waiting', 'in_consultation'])
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Patient déjà en salle d\'attente');
        }

        WaitingRoom::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_id' => $request->appointment_id,
            'arrival_time' => now(),
            'priority' => $request->priority ?? 0,
            'status' => 'waiting',
        ]);

        return redirect()->route('secretaire.waiting-room')
            ->with('success', 'Patient ajouté à la salle d\'attente');
    }

    // Démarrer consultation (médecin)
    public function startConsultation(WaitingRoom $waitingRoom)
    {
        $waitingRoom->update([
            'status' => 'in_consultation',
            'start_time' => now(),
        ]);

        return redirect()->route('doctor.consultations.create', [
            'patient' => $waitingRoom->patient_id,
            'appointment' => $waitingRoom->appointment_id ?? 0,
            'waiting_id' => $waitingRoom->id
        ])->with('success', 'Consultation démarrée');
    }

    // Terminer consultation (médecin)
    public function complete(WaitingRoom $waitingRoom)
    {
        $waitingRoom->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);

        if ($waitingRoom->appointment_id) {
            $appointment = Appointment::find($waitingRoom->appointment_id);
            if ($appointment && $appointment->status != 'completed') {
                $appointment->update(['status' => 'completed']);
            }
        }

        return redirect()->route('doctor.waiting-room')
            ->with('success', 'Consultation terminée');
    }

    // Retirer un patient (secrétaire)
    public function remove(WaitingRoom $waitingRoom)
    {
        $waitingRoom->delete();
        
        return redirect()->route('secretaire.waiting-room')
            ->with('success', 'Patient retiré de la salle d\'attente');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialite;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:chef_medecine')->except(['index', 'show', 'myPatients', 'notifications', 'markAllNotifications', 'markNotificationRead']);
    }

    public function index()
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialites = Specialite::all();
        return view('admin.doctors.create', compact('specialites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
            'specialty' => 'required',
            'registration_number' => 'required|unique:doctors',
            'consultation_fee' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'specialite_id' => $request->specialite_id,
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialty' => $request->specialty,
            'registration_number' => $request->registration_number,
            'consultation_fee' => $request->consultation_fee,
            'diploma' => $request->diploma,
            'cabinet_phone' => $request->cabinet_phone,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Médecin ajouté avec succès');
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('user');
        $specialites = Specialite::all();
        return view('admin.doctors.edit', compact('doctor', 'specialites'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'phone' => 'required',
            'specialty' => 'required',
            'registration_number' => 'required|unique:doctors,registration_number,' . $doctor->id,
            'consultation_fee' => 'required|numeric',
        ]);

        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'specialite_id' => $request->specialite_id,
        ]);

        $doctor->update([
            'specialty' => $request->specialty,
            'registration_number' => $request->registration_number,
            'consultation_fee' => $request->consultation_fee,
            'diploma' => $request->diploma,
            'cabinet_phone' => $request->cabinet_phone,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Médecin modifié avec succès');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Médecin supprimé avec succès');
    }

    // ========== MÉTHODES POUR LE MÉDECIN ==========

    public function myPatients()
    {
        $doctorId = auth()->user()->doctor->id;
        
        $patients = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->select('patient_id')
            ->distinct()
            ->get()
            ->pluck('patient');
        
        return view('doctor.patients', compact('patients'));
    }

    public function notifications()
    {
        return view('doctor.notifications');
    }

    public function markAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }
    /**
 * Afficher le dossier médical d'un patient (pour le médecin)
 */
public function showPatient(Patient $patient)
{
    // Vérifier que le patient a consulté ce médecin
    $doctorId = auth()->user()->doctor->id;
    $hasConsulted = $patient->consultations()
        ->where('doctor_id', $doctorId)
        ->exists();
    
    // Le chef de médecine peut voir tous les dossiers
    if (!$hasConsulted && auth()->user()->role != 'chef_medecine') {
        abort(403, 'Vous n\'avez pas accès au dossier de ce patient');
    }
    
    $patient->load([
        'user', 
        'appointments' => function($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId)
              ->orderBy('date_time', 'desc');
        }, 
        'consultations' => function($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId)
              ->orderBy('consultation_date', 'desc');
        }, 
        'prescriptions' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 
        'invoices'
    ]);
    
    // Statistiques du patient
    $stats = [
        'total_appointments' => $patient->appointments->count(),
        'total_consultations' => $patient->consultations->count(),
        'total_prescriptions' => $patient->prescriptions->count(),
        'last_visit' => $patient->consultations->first()?->consultation_date,
    ];
    
    return view('doctor.patient-show', compact('patient', 'stats'));
}
}
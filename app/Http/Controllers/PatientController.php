<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Notifications\SystemNotification; // ⚠️ AJOUTER CETTE LIGNE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $patients = Patient::with('user')->get();
        return view('secretaire.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('secretaire.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
            'birth_date' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'insurance_number' => $request->insurance_number,
            'insurance_company' => $request->insurance_company,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
            'allergies' => $request->allergies,
            'medical_history' => $request->medical_history,
            'blood_type' => $request->blood_type,
            'weight' => $request->weight,
            'height' => $request->height,
            'has_cnam' => $request->has_cnam ?? false,
            'cnam_number' => $request->cnam_number,
            'cnam_expiry_date' => $request->cnam_expiry_date,
            'has_mutuelle' => $request->has_mutuelle ?? false,
            'mutuelle_number' => $request->mutuelle_number,
            'mutuelle_company' => $request->mutuelle_company,
            'mutuelle_rate' => $request->mutuelle_rate,
            'mutuelle_expiry_date' => $request->mutuelle_expiry_date,
        ]);

        // 🔔 Notifier la secrétaire du nouveau patient
        $secretaries = User::where('role', 'secretaire')->get();
        foreach ($secretaries as $secretary) {
            $secretary->notify(new SystemNotification('patient.new_registration', [
                'name' => $secretary->name,
                'patient_name' => $user->name,
                'patient_email' => $user->email,
                'patient_phone' => $user->phone,
                'patient_id' => $patient->id,
            ]));
        }

        return redirect()->to('/secretaire/patients')->with('success', 'Patient ajouté');
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'appointments', 'consultations']);
        return view('secretaire.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $patient->load('user');
        return view('secretaire.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'phone' => 'required',
            'birth_date' => 'required|date',
        ]);

        $patient->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
        ]);

        $patient->update([
            'insurance_number' => $request->insurance_number,
            'insurance_company' => $request->insurance_company,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
            'allergies' => $request->allergies,
            'medical_history' => $request->medical_history,
            'blood_type' => $request->blood_type,
            'weight' => $request->weight,
            'height' => $request->height,
            'has_cnam' => $request->has_cnam ?? false,
            'cnam_number' => $request->cnam_number,
            'cnam_expiry_date' => $request->cnam_expiry_date,
            'has_mutuelle' => $request->has_mutuelle ?? false,
            'mutuelle_number' => $request->mutuelle_number,
            'mutuelle_company' => $request->mutuelle_company,
            'mutuelle_rate' => $request->mutuelle_rate,
            'mutuelle_expiry_date' => $request->mutuelle_expiry_date,
        ]);

        return redirect()->to('/secretaire/patients')->with('success', 'Patient modifié');
    }

    public function destroy(Patient $patient)
    {
        $patient->user->delete();
        $patient->delete();
        return redirect()->to('/secretaire/patients')->with('success', 'Patient supprimé');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $patients = Patient::with('user')
            ->whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($patients);
    }

    // ==================== 🔔 NOTIFICATIONS PATIENT ====================
    
    /**
     * Afficher toutes les notifications du patient
     */
    public function notifications()
    {
        return view('patient.notifications');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Marquer une notification spécifique comme lue
     */
    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }
}
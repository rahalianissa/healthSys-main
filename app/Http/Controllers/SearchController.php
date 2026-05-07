<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Invoice;
use App\Models\Consultation;
use App\Models\Specialite;
use App\Models\Departement;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Recherche globale - version HTML (pour la page dédiée)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Minimum 2 caractères']);
            }
            return redirect()->back()->with('error', 'Veuillez entrer au moins 2 caractères');
        }

        $user = auth()->user();
        $results = [];

        // ========== ROLE: ADMIN (CHEF MEDECINE) ==========
        if ($user->role == 'chef_medecine') {
            // Patients
            $results['patients'] = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })->limit(10)->get()->map(fn($p) => [
                    'name' => $p->user->name, 'email' => $p->user->email, 'phone' => $p->user->phone,
                    'url' => route('secretaire.patients.show', $p)
                ]);

            // Médecins
            $results['doctors'] = Doctor::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->limit(10)->get()->map(fn($d) => [
                    'name' => 'Dr. ' . $d->user->name, 'specialty' => $d->specialty,
                    'url' => route('admin.doctors.edit', $d)
                ]);

            // Départements & Spécialités
            $results['specialites'] = Specialite::where('nom', 'like', "%{$query}%")->limit(5)->get()->map(fn($s) => [
                'name' => $s->nom, 'url' => route('admin.specialites.index')
            ]);
        }

        // ========== ROLE: SECRETAIRE ==========
        elseif ($user->role == 'secretaire') {
            // Patients
            $results['patients'] = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })->limit(10)->get()->map(fn($p) => [
                    'name' => $p->user->name, 'email' => $p->user->email, 'phone' => $p->user->phone,
                    'url' => route('secretaire.patients.show', $p)
                ]);

            // Factures
            $results['invoices'] = Invoice::where('invoice_number', 'like', "%{$query}%")
                ->orWhereHas('patient.user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->limit(10)->get()->map(fn($i) => [
                    'name' => "Facture " . $i->invoice_number, 'amount' => $i->amount . " DT",
                    'url' => route('invoices.show', $i)
                ]);

            // Rendez-vous
            $results['appointments'] = Appointment::with(['patient.user', 'doctor.user'])
                ->whereHas('patient.user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->limit(10)->get()->map(fn($a) => [
                    'patient' => $a->patient->user->name, 'doctor' => 'Dr. ' . $a->doctor->user->name,
                    'date' => $a->date_time->format('d/m/Y H:i'), 'status' => $a->status,
                    'url' => route('secretaire.appointments.show', $a)
                ]);
        }

        // ========== ROLE: DOCTOR ==========
        elseif ($user->role == 'doctor') {
            $doctorId = $user->doctor->id;
            
            // Mes Patients
            $results['patients'] = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->whereHas('appointments', function($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })->limit(10)->get()->map(fn($p) => [
                    'name' => $p->user->name, 'phone' => $p->user->phone,
                    'url' => route('doctor.patients.show', $p)
                ]);

            // Mes Consultations
            $results['consultations'] = Consultation::where('doctor_id', $doctorId)
                ->whereHas('patient.user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->limit(10)->get()->map(fn($c) => [
                    'name' => "Consultation: " . $c->patient->user->name, 'date' => $c->created_at->format('d/m/Y'),
                    'url' => route('doctor.consultations.show', $c->id)
                ]);
        }

        // ========== ROLE: PATIENT ==========
        elseif ($user->role == 'patient') {
            $patientId = $user->patient->id ?? null;
            if ($patientId) {
                // Mes RDV
                $results['appointments'] = Appointment::where('patient_id', $patientId)
                    ->whereHas('doctor.user', function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })->limit(5)->get()->map(fn($a) => [
                        'doctor' => 'Dr. ' . $a->doctor->user->name, 'date' => $a->date_time->format('d/m/Y H:i'),
                        'url' => route('patient.appointments')
                    ]);

                // Mes Ordonnances
                $results['prescriptions'] = Prescription::where('patient_id', $patientId)
                    ->whereHas('doctor.user', function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })->limit(5)->get()->map(fn($p) => [
                        'doctor' => 'Dr. ' . $p->doctor->user->name, 'date' => $p->created_at->format('d/m/Y'),
                        'url' => route('prescriptions.show', $p)
                    ]);
            }
        }

        $total = collect($results)->flatten(1)->count();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'total' => $total, 'results' => $results]);
        }

        return view('search.results', compact('results', 'query', 'total'));
    }

    /**
     * Autocomplete pour la barre de recherche (AJAX) - Adapté aux rôles
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) return response()->json([]);

        $user = auth()->user();
        $suggestions = [];

        // ADMIN Suggests (Global)
        if ($user->role == 'chef_medecine') {
            $doctors = User::where('role', 'doctor')->where('name', 'like', "%{$query}%")->limit(3)->get();
            foreach($doctors as $d) $suggestions[] = ['label' => $d->name, 'subtitle' => 'Médecin', 'icon' => 'fas fa-user-md', 'url' => '#'];
            
            $patients = User::where('role', 'patient')->where('name', 'like', "%{$query}%")->limit(3)->get();
            foreach($patients as $p) $suggestions[] = ['label' => $p->name, 'subtitle' => 'Patient', 'icon' => 'fas fa-user', 'url' => '#'];
        }

        // SECRETAIRE Suggests (Management)
        elseif ($user->role == 'secretaire') {
            $patients = Patient::whereHas('user', fn($q) => $q->where('name', 'like', "%{$query}%"))->limit(5)->get();
            foreach($patients as $p) $suggestions[] = ['label' => $p->user->name, 'subtitle' => 'Dossier Patient', 'icon' => 'fas fa-file-medical', 'url' => route('secretaire.patients.show', $p)];
        }

        // DOCTOR Suggests (Medical)
        elseif ($user->role == 'doctor') {
            $doctorId = $user->doctor->id;
            $myPatients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctorId))
                ->whereHas('user', fn($q) => $q->where('name', 'like', "%{$query}%"))->limit(5)->get();
            foreach($myPatients as $p) $suggestions[] = ['label' => $p->user->name, 'subtitle' => 'Mon Patient', 'icon' => 'fas fa-user-injured', 'url' => route('doctor.patients.show', $p)];
        }

        // PATIENT Suggests (Personal)
        elseif ($user->role == 'patient') {
            $suggestions[] = ['label' => 'Voir mes rendez-vous', 'subtitle' => 'Accès rapide', 'icon' => 'fas fa-calendar', 'url' => route('patient.appointments')];
            $suggestions[] = ['label' => 'Mes ordonnances', 'subtitle' => 'Accès rapide', 'icon' => 'fas fa-prescription', 'url' => route('patient.prescriptions')];
        }

        return response()->json($suggestions);
    }
}
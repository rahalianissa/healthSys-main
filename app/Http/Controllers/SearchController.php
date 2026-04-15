<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
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

        // ========== ADMIN ==========
        if ($user->role == 'chef_medecine') {
            $results['patients'] = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get()
                ->map(function($patient) {
                    return [
                        'id' => $patient->id,
                        'type' => 'patient',
                        'name' => $patient->user->name,
                        'email' => $patient->user->email,
                        'phone' => $patient->user->phone,
                        'url' => route('secretaire.patients.show', $patient)
                    ];
                });

            $results['doctors'] = Doctor::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function($doctor) {
                    return [
                        'id' => $doctor->id,
                        'type' => 'doctor',
                        'name' => 'Dr. ' . $doctor->user->name,
                        'specialty' => $doctor->specialty,
                        'url' => route('admin.doctors.edit', $doctor)
                    ];
                });

            $results['appointments'] = Appointment::with(['patient.user', 'doctor.user'])
                ->where(function($q) use ($query) {
                    $q->whereHas('patient.user', function($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%");
                    })->orWhereHas('doctor.user', function($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%");
                    });
                })
                ->limit(10)
                ->get()
                ->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'type' => 'appointment',
                        'patient' => $appointment->patient->user->name,
                        'doctor' => 'Dr. ' . $appointment->doctor->user->name,
                        'date' => $appointment->date_time->format('d/m/Y H:i'),
                        'status' => $appointment->status,
                        'url' => route('secretaire.appointments.show', $appointment)
                    ];
                });
        }

        // ========== SECRETAIRE ==========
        elseif ($user->role == 'secretaire') {
            $results['patients'] = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get()
                ->map(function($patient) {
                    return [
                        'id' => $patient->id,
                        'type' => 'patient',
                        'name' => $patient->user->name,
                        'email' => $patient->user->email,
                        'phone' => $patient->user->phone,
                        'url' => route('secretaire.patients.show', $patient)
                    ];
                });

            $results['appointments'] = Appointment::with(['patient.user', 'doctor.user'])
                ->where(function($q) use ($query) {
                    $q->whereHas('patient.user', function($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%");
                    })->orWhereHas('doctor.user', function($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%");
                    });
                })
                ->limit(10)
                ->get()
                ->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'type' => 'appointment',
                        'patient' => $appointment->patient->user->name,
                        'doctor' => 'Dr. ' . $appointment->doctor->user->name,
                        'date' => $appointment->date_time->format('d/m/Y H:i'),
                        'status' => $appointment->status,
                        'url' => route('secretaire.appointments.show', $appointment)
                    ];
                });
        }

        // ========== MEDECIN ==========
        elseif ($user->role == 'doctor') {
            $doctorId = $user->doctor->id;
            
            $results['patients'] = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->whereHas('appointments', function($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->limit(10)
                ->get()
                ->map(function($patient) {
                    return [
                        'id' => $patient->id,
                        'type' => 'patient',
                        'name' => $patient->user->name,
                        'email' => $patient->user->email,
                        'phone' => $patient->user->phone,
                        'url' => route('doctor.patients.show', $patient)
                    ];
                });

            $results['appointments'] = Appointment::with(['patient.user'])
                ->where('doctor_id', $doctorId)
                ->whereHas('patient.user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get()
                ->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'type' => 'appointment',
                        'patient' => $appointment->patient->user->name,
                        'date' => $appointment->date_time->format('d/m/Y H:i'),
                        'status' => $appointment->status,
                        'url' => route('secretaire.appointments.show', $appointment)
                    ];
                });
        }

        // ========== PATIENT ==========
        elseif ($user->role == 'patient') {
            $patientId = $user->patient->id ?? null;
            
            if ($patientId) {
                $results['appointments'] = Appointment::where('patient_id', $patientId)
                    ->with('doctor.user')
                    ->whereHas('doctor.user', function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })
                    ->limit(10)
                    ->get()
                    ->map(function($appointment) {
                        return [
                            'id' => $appointment->id,
                            'type' => 'appointment',
                            'doctor' => 'Dr. ' . $appointment->doctor->user->name,
                            'date' => $appointment->date_time->format('d/m/Y H:i'),
                            'status' => $appointment->status,
                            'url' => route('patient.appointments')
                        ];
                    });

                $results['prescriptions'] = Prescription::where('patient_id', $patientId)
                    ->with('doctor.user')
                    ->whereHas('doctor.user', function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })
                    ->limit(10)
                    ->get()
                    ->map(function($prescription) {
                        return [
                            'id' => $prescription->id,
                            'type' => 'prescription',
                            'doctor' => 'Dr. ' . $prescription->doctor->user->name,
                            'date' => $prescription->created_at->format('d/m/Y'),
                            'url' => route('prescriptions.show', $prescription)
                        ];
                    });
            }
        }

        $total = 0;
        foreach ($results as $category) {
            $total += $category->count();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'total' => $total,
                'results' => $results
            ]);
        }

        return view('search.results', compact('results', 'query', 'total'));
    }

    /**
     * Autocomplete pour la barre de recherche (AJAX)
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $user = auth()->user();
        $suggestions = [];

        if (in_array($user->role, ['chef_medecine', 'secretaire'])) {
            // Patients
            $patients = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($patients as $patient) {
                $suggestions[] = [
                    'id' => $patient->id,
                    'type' => 'patient',
                    'label' => $patient->user->name,
                    'subtitle' => $patient->user->email ?? $patient->user->phone,
                    'icon' => 'fas fa-user',
                    'url' => route('secretaire.patients.show', $patient)
                ];
            }

            // Rendez-vous
            $appointments = Appointment::with(['patient.user', 'doctor.user'])
                ->where(function($q) use ($query) {
                    $q->whereHas('patient.user', function($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%");
                    })->orWhereHas('doctor.user', function($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%");
                    });
                })
                ->limit(5)
                ->get();

            foreach ($appointments as $appointment) {
                $suggestions[] = [
                    'id' => $appointment->id,
                    'type' => 'appointment',
                    'label' => $appointment->patient->user->name,
                    'subtitle' => 'RDV avec Dr. ' . $appointment->doctor->user->name . ' - ' . $appointment->date_time->format('d/m/Y H:i'),
                    'icon' => 'fas fa-calendar-check',
                    'url' => route('secretaire.appointments.show', $appointment)
                ];
            }
        }
        elseif ($user->role == 'doctor') {
            $doctorId = $user->doctor->id;
            
            $patients = Patient::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->whereHas('appointments', function($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->limit(5)
                ->get();

            foreach ($patients as $patient) {
                $suggestions[] = [
                    'id' => $patient->id,
                    'type' => 'patient',
                    'label' => $patient->user->name,
                    'subtitle' => $patient->user->phone ?? $patient->user->email,
                    'icon' => 'fas fa-user-injured',
                    'url' => route('doctor.patients.show', $patient)
                ];
            }
        }
        elseif ($user->role == 'patient') {
            $patientId = $user->patient->id ?? null;
            
            if ($patientId) {
                $appointments = Appointment::where('patient_id', $patientId)
                    ->with('doctor.user')
                    ->whereHas('doctor.user', function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })
                    ->limit(5)
                    ->get();

                foreach ($appointments as $appointment) {
                    $suggestions[] = [
                        'id' => $appointment->id,
                        'type' => 'appointment',
                        'label' => 'Dr. ' . $appointment->doctor->user->name,
                        'subtitle' => $appointment->date_time->format('d/m/Y H:i'),
                        'icon' => 'fas fa-calendar-alt',
                        'url' => route('patient.appointments')
                    ];
                }
            }
        }

        return response()->json($suggestions);
    }
}
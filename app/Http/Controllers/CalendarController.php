<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $doctors = [];
        
        // Pour les secrétaires et chefs de médecine, récupérer tous les médecins
        if (in_array($user->role, ['secretaire', 'chef_medecine', 'secretary', 'admin'])) {
            $doctors = Doctor::with('user')->get();
        }
        
        return view('doctor.calendar', compact('doctors'));
    }
    
    public function events(Request $request)
    {
        $user = auth()->user();
        $doctorId = $request->query('doctor_id');
        
        // Vérification des droits d'accès
        if (!in_array($user->role, ['doctor', 'secretaire', 'chef_medecine', 'secretary', 'admin', 'patient'])) {
            return response()->json([]);
        }
        
        // Cas 1: Médecin - voir uniquement ses propres rendez-vous
        if (in_array($user->role, ['doctor', 'medecin', 'docteur']) && $user->doctor) {
            $appointments = Appointment::with(['patient.user'])
                ->where('doctor_id', $user->doctor->id)
                ->get();
        } 
        // Cas 2: Secrétaire ou Admin - voir tous les rendez-vous ou filtrer par médecin
        elseif (in_array($user->role, ['secretaire', 'chef_medecine', 'secretary', 'admin'])) {
            $query = Appointment::with(['patient.user', 'doctor.user']);
            
            // Filtrer par médecin spécifique si demandé
            if ($doctorId && $doctorId !== 'all' && $doctorId !== 'null') {
                $query->where('doctor_id', $doctorId);
            }
            // Si la secrétaire a un département, filtrer par les médecins de son département
            elseif ($user->departement_id && !in_array($user->role, ['chef_medecine', 'admin'])) {
                $query->whereHas('doctor', function($q) use ($user) {
                    $q->where('departement_id', $user->departement_id);
                });
            }
            
            $appointments = $query->get();
        } 
        // Cas 3: Patient - voir uniquement ses propres rendez-vous
        elseif (in_array($user->role, ['patient', 'patient_consultation']) && $user->patient) {
            $appointments = Appointment::with(['doctor.user'])
                ->where('patient_id', $user->patient->id)
                ->get();
        } 
        else {
            return response()->json([]);
        }
        
        // Mapping des couleurs selon le statut
        $colors = [
            'pending' => '#F59E0B',    // Orange - En attente
            'confirmed' => '#10B981',  // Vert - Confirmé
            'scheduled' => '#10B981',  // Vert - Programmé
            'cancelled' => '#EF4444',  // Rouge - Annulé
            'completed' => '#6366F1',  // Indigo - Terminé
        ];
        
        $events = [];
        
        foreach ($appointments as $appointment) {
            $patientName = $appointment->patient?->user?->name ?? 'Patient inconnu';
            $doctorName = $appointment->doctor?->user?->name ?? 'Médecin';
            
            // Déterminer le titre selon le rôle
            $title = '';
            if (in_array($user->role, ['doctor', 'medecin', 'docteur'])) {
                $title = $patientName;
            } elseif (in_array($user->role, ['secretaire', 'chef_medecine', 'secretary', 'admin'])) {
                $title = $patientName . ' (Dr. ' . $doctorName . ')';
            } else {
                $title = 'Dr. ' . $doctorName;
            }
            
            $events[] = [
                'id' => $appointment->id,
                'title' => $title,
                'start' => $appointment->date_time->toIso8601String(),
                'end' => $appointment->date_time->copy()->addMinutes($appointment->duration)->toIso8601String(),
                'color' => $colors[$appointment->status] ?? '#6c757d',
                'extendedProps' => [
                    'status' => $appointment->status,
                    'patient' => $patientName,
                    'patient_name' => $patientName,
                    'phone' => $appointment->patient?->user?->phone ?? 'Non renseigné',
                    'email' => $appointment->patient?->user?->email ?? 'Non renseigné',
                    'reason' => $appointment->reason,
                    'notes' => $appointment->notes,
                    'duration' => $appointment->duration,
                    'doctor' => $doctorName,
                    'doctor_id' => $appointment->doctor_id
                ]
            ];
        }
        
        return response()->json($events);
    }
    
    // Méthode pour récupérer la liste des médecins (pour le filtre)
    public function getDoctors(Request $request)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['secretaire', 'chef_medecine', 'secretary', 'admin'])) {
            return response()->json([]);
        }
        
        $query = Doctor::with('user');
        
        // Filtrer par département si la secrétaire a un département
        if ($user->departement_id && !in_array($user->role, ['chef_medecine', 'admin'])) {
            $query->where('departement_id', $user->departement_id);
        }
        
        $doctors = $query->get()->map(function($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'specialty' => $doctor->specialty
            ];
        });
        
        return response()->json($doctors);
    }
}
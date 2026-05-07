<?php

namespace App\Http\Controllers;

use App\Domain\Appointment\Models\Appointment;
use App\Domain\User\Models\Patient;
use App\Domain\Billing\Invoice\Models\Invoice;
use App\Domain\User\Models\Doctor;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:chef_medecine,secretaire');
    }

    /**
     * Export patients to CSV
     */
    public function patients(Request $request)
    {
        $patients = Patient::with('user')->get();
        
        $filename = 'patients_' . date('Y-m-d') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        // UTF-8 BOM for Excel compatibility
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($handle, [
            'ID', 'Nom complet', 'Email', 'Téléphone', 'Date naissance',
            'Âge', 'Adresse', 'Groupe sanguin', 'Mutuelle', 'Numéro mutuelle',
            'Allergies', 'Antécédents', 'Contact urgence', 'Téléphone urgence',
            'Date inscription'
        ]);
        
        // Data
        foreach ($patients as $patient) {
            fputcsv($handle, [
                $patient->id,
                $patient->user->name ?? '',
                $patient->user->email ?? '',
                $patient->user->phone ?? '',
                $patient->user->birth_date ? date('d/m/Y', strtotime($patient->user->birth_date)) : '',
                $patient->age ?? '',
                $patient->user->address ?? '',
                $patient->blood_type ?? '',
                $patient->insurance_company ?? '',
                $patient->insurance_number ?? '',
                $patient->allergies ?? '',
                $patient->medical_history ?? '',
                $patient->emergency_contact ?? '',
                $patient->emergency_phone ?? '',
                $patient->created_at ? $patient->created_at->format('d/m/Y') : '',
            ]);
        }
        
        fclose($handle);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        exit;
    }

    /**
     * Export appointments to CSV
     */
    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user']);
        
        if ($request->start_date) {
            $query->whereDate('date_time', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('date_time', '<=', $request->end_date);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        $appointments = $query->orderBy('date_time', 'desc')->get();
        
        $filename = 'rendez-vous_' . date('Y-m-d') . '.csv';
        
        $handle = fopen('php://output', 'w');
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($handle, [
            '#', 'Date et heure', 'Patient', 'Email patient', 'Téléphone patient',
            'Médecin', 'Spécialité', 'Type', 'Statut', 'Motif', 'Durée (min)'
        ]);
        
        // Data
        $rowNumber = 0;
        foreach ($appointments as $appointment) {
            $rowNumber++;
            
            $statusLabels = [
                'pending' => 'En attente',
                'confirmed' => 'Confirmé',
                'cancelled' => 'Annulé',
                'completed' => 'Terminé'
            ];
            
            $typeLabels = [
                'general' => 'Général',
                'emergency' => 'Urgence',
                'follow_up' => 'Suivi',
                'specialist' => 'Spécialiste'
            ];
            
            fputcsv($handle, [
                $rowNumber,
                $appointment->date_time ? $appointment->date_time->format('d/m/Y H:i') : '',
                $appointment->patient->user->name ?? '',
                $appointment->patient->user->email ?? '',
                $appointment->patient->user->phone ?? '',
                'Dr. ' . ($appointment->doctor->user->name ?? ''),
                $appointment->doctor->specialty ?? '',
                $typeLabels[$appointment->type] ?? $appointment->type,
                $statusLabels[$appointment->status] ?? $appointment->status,
                $appointment->reason ?? '',
                $appointment->duration ?? 30,
            ]);
        }
        
        fclose($handle);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        exit;
    }

    /**
     * Export invoices to CSV
     */
    public function invoices(Request $request)
    {
        $query = Invoice::with(['patient.user']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $invoices = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'factures_' . date('Y-m-d') . '.csv';
        
        $handle = fopen('php://output', 'w');
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($handle, [
            '#', 'N° Facture', 'Patient', 'Email', 'Montant (DT)',
            'Payé (DT)', 'Reste (DT)', 'Statut', 'Date création'
        ]);
        
        // Data
        $rowNumber = 0;
        foreach ($invoices as $invoice) {
            $rowNumber++;
            $remaining = $invoice->amount - $invoice->paid_amount;
            
            $statusLabels = [
                'pending' => 'En attente',
                'paid' => 'Payée',
                'partially_paid' => 'Partiellement payée',
                'cancelled' => 'Annulée'
            ];
            
            fputcsv($handle, [
                $rowNumber,
                $invoice->invoice_number,
                $invoice->patient->user->name ?? '',
                $invoice->patient->user->email ?? '',
                number_format($invoice->amount, 2),
                number_format($invoice->paid_amount, 2),
                number_format($remaining, 2),
                $statusLabels[$invoice->status] ?? $invoice->status,
                $invoice->created_at ? $invoice->created_at->format('d/m/Y') : '',
            ]);
        }
        
        fclose($handle);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        exit;
    }
}
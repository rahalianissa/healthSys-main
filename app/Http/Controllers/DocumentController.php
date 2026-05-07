<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('documents.index');
    }

    public function search(Request $request)
    {
        $search = $request->q;
        
        $patient = Patient::with(['user', 'documents'])
            ->whereHas('user', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->first();
            
        if ($patient) {
            $documents = $patient->documents->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'type' => $doc->type_label,
                    'type_icon' => $doc->type_icon,
                    'date' => $doc->created_at->format('d/m/Y H:i'),
                    'url' => Storage::url($doc->file_path),
                ];
            });

            return response()->json([
                'success' => true,
                'patient' => [
                    'name' => $patient->user->name,
                    'cin' => $patient->insurance_number ?? $patient->user->phone ?? 'N/A',
                ],
                'documents' => $documents
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Aucun patient trouvé'
        ]);
    }

    public function print($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.print', compact('document'));
    }

    public function establish()
    {
        return view('doctor.establish-document');
    }

    public function storePrescription(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medications' => 'required|array',
            'instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => auth()->user()->doctor->id,
            'medications' => $request->medications, // Model casts this to array/json automatically
            'instructions' => $request->instructions,
            'prescription_date' => now(),
            'status' => 'active',
        ]);

        $pdf = Pdf::loadView('pdf.prescription', compact('prescription'));
        $filename = 'ordonnance_' . $prescription->id . '_' . date('Y-m-d') . '.pdf';
        $path = 'documents/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Sauvegarder dans la table Document pour que ça apparaisse dans le dossier patient
        Document::create([
            'patient_id' => $request->patient_id,
            'title' => 'Ordonnance - ' . date('d/m/Y'),
            'type' => 'prescription',
            'file_path' => $path,
            'file_name' => $filename,
            'file_type' => 'application/pdf',
            'file_size' => Storage::disk('public')->size($path),
            'description' => $request->instructions ?? 'Ordonnance médicale',
        ]);

        return response()->json([
            'success' => true, 
            'pdf_url' => Storage::url($path)
        ]);
    }

    public function storeCertificate(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|string',
            'duration' => 'required|integer',
            'reason' => 'nullable|string',
        ]);

        $patient = Patient::with('user')->find($request->patient_id);
        $doctor = auth()->user()->doctor;
        
        $data = [
            'patient' => $patient,
            'type' => $request->type,
            'duration' => $request->duration,
            'reason' => $request->reason,
            'date' => now(),
            'doctor' => $doctor,
        ];

        $pdf = Pdf::loadView('pdf.certificate', $data);
        $filename = 'certificat_' . $patient->id . '_' . date('Y-m-d') . '.pdf';
        $path = 'documents/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'patient_id' => $request->patient_id,
            'title' => 'Certificat: ' . ucfirst($request->type),
            'type' => 'certificate',
            'file_path' => $path,
            'file_name' => $filename,
            'file_type' => 'application/pdf',
            'file_size' => Storage::disk('public')->size($path),
            'description' => $request->reason ?? 'Certificat médical de type ' . $request->type,
        ]);

        return response()->json([
            'success' => true,
            'pdf_url' => Storage::url($path)
        ]);
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $patient = Patient::with('user')->find($request->patient_id);
        $doctor = auth()->user()->doctor;
        
        $data = [
            'patient' => $patient,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'recommendations' => $request->recommendations,
            'date' => now(),
            'doctor' => $doctor,
        ];

        $pdf = Pdf::loadView('pdf.report', $data);
        $filename = 'compte_rendu_' . $patient->id . '_' . date('Y-m-d') . '.pdf';
        $path = 'documents/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'patient_id' => $request->patient_id,
            'title' => 'Compte-rendu - ' . date('d/m/Y'),
            'type' => 'report',
            'file_path' => $path,
            'file_name' => $filename,
            'file_type' => 'application/pdf',
            'file_size' => Storage::disk('public')->size($path),
            'description' => $request->diagnosis ?? 'Compte-rendu médical',
        ]);

        return response()->json([
            'success' => true,
            'pdf_url' => Storage::url($path)
        ]);
    }
}
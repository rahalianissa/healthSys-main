<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Invoice;
use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PatientController extends ApiController
{
    /**
     * List all patients (for secretary/admin)
     */
    public function index(): JsonResponse
    {
        $patients = Patient::with('user')->get();
        return $this->success($patients);
    }

    /**
     * Store a new patient
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
            'birth_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

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
        ]);

        return $this->success($patient->load('user'), 'Patient créé avec succès', 201);
    }

    /**
     * Show a specific patient
     */
    public function show(int $id): JsonResponse
    {
        $patient = Patient::with(['user', 'appointments', 'consultations'])->find($id);
        if (!$patient) {
            return $this->error('Patient non trouvé', 404);
        }
        return $this->success($patient);
    }

    /**
     * Delete a patient
     */
    public function destroy(int $id): JsonResponse
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return $this->error('Patient non trouvé', 404);
        }
        
        $user = $patient->user;
        $patient->delete();
        if ($user) $user->delete();

        return $this->success(null, 'Patient supprimé');
    }

    /**
     * Get patient's medical record
     */
    public function medicalRecord(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isPatient()) {
            return $this->error('Non autorisé', 403);
        }

        $patient = $user->patient->load(['user']);
        return $this->success($patient);
    }

    /**
     * Update patient's profile/medical info
     */
    public function updateMedicalRecord(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isPatient()) {
            return $this->error('Non autorisé', 403);
        }

        $patient = $user->patient;
        
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        // Update User info
        if ($request->has('phone') || $request->has('address')) {
            $user->update($request->only(['phone', 'address']));
        }

        // Update Patient info
        $patient->update($request->only([
            'blood_type', 'allergies', 'medical_history', 'weight', 'height'
        ]));

        return $this->success($patient->load('user'), 'Dossier médical mis à jour');
    }

    /**
     * Get patient's prescriptions
     */
    public function prescriptions(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isPatient()) {
            return $this->error('Non autorisé', 403);
        }

        $prescriptions = Prescription::with(['doctor.user', 'consultation'])
            ->where('patient_id', $user->patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success($prescriptions);
    }

    /**
     * Get patient's invoices
     */
    public function invoices(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isPatient()) {
            return $this->error('Non autorisé', 403);
        }

        $invoices = Invoice::where('patient_id', $user->patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success($invoices);
    }

    /**
     * Get patient's consultations
     */
    public function consultations(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isPatient()) {
            return $this->error('Non autorisé', 403);
        }

        $consultations = Consultation::with(['doctor.user', 'prescription'])
            ->where('patient_id', $user->patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success($consultations);
    }
}

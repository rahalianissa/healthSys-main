<?php

namespace App\Http\Controllers\Api;

use App\Models\Consultation;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConsultationController extends ApiController
{
    public function index(): JsonResponse
    {
        $consultations = Consultation::with(['patient.user', 'doctor.user'])->get();
        return $this->success($consultations);
    }

    public function doctorIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isDoctor()) {
            return $this->error('Non autorisé', 403);
        }

        $consultations = Consultation::with(['patient.user'])
            ->where('doctor_id', $user->doctor->id)
            ->orderBy('consultation_date', 'desc')
            ->get();

        return $this->success($consultations);
    }

    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $consultation = Consultation::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->user()->doctor->id,
            'appointment_id' => $request->appointment_id,
            'consultation_date' => now(),
            'status' => 'in_progress',
        ]);

        if ($request->appointment_id) {
            Appointment::where('id', $request->appointment_id)->update(['status' => 'ongoing']);
        }

        return $this->success($consultation, 'Consultation démarrée', 201);
    }

    public function show(int $id): JsonResponse
    {
        $consultation = Consultation::with(['patient.user', 'doctor.user', 'prescription'])->find($id);
        if (!$consultation) {
            return $this->error('Consultation non trouvée', 404);
        }
        return $this->success($consultation);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WaitingRoomController extends ApiController
{
    public function secretaryIndex(): JsonResponse
    {
        $waiting = WaitingRoom::with(['patient.user', 'doctor.user'])
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('arrival_time', 'asc')
            ->get();

        return $this->success($waiting);
    }

    public function doctorIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isDoctor()) {
            return $this->error('Non autorisé', 403);
        }

        $waiting = WaitingRoom::with(['patient.user'])
            ->where('doctor_id', $user->doctor->id)
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('arrival_time', 'asc')
            ->get();

        return $this->success($waiting);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'priority' => 'nullable|integer',
        ]);

        $waiting = WaitingRoom::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'arrival_time' => now(),
            'status' => 'waiting',
            'priority' => $request->priority ?? 0,
        ]);

        return $this->success($waiting, 'Patient ajouté à la salle d\'attente', 201);
    }

    public function callNext(Request $request): JsonResponse
    {
        $doctor = $request->user()->doctor;
        if (!$doctor) return $this->error('Non autorisé', 403);

        $next = WaitingRoom::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('arrival_time', 'asc')
            ->first();

        if (!$next) {
            return $this->error('Aucun patient en attente', 404);
        }

        $next->update([
            'status' => 'in_consultation',
            'start_time' => now(),
        ]);

        return $this->success($next, 'Patient appelé');
    }

    public function remove(int $id): JsonResponse
    {
        $waiting = WaitingRoom::find($id);
        if (!$waiting) {
            return $this->error('Non trouvé', 404);
        }

        $waiting->delete();
        return $this->success(null, 'Patient retiré de la salle d\'attente');
    }
}

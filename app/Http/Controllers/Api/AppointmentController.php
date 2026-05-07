<?php

namespace App\Http\Controllers\Api;

use App\Domain\Appointment\Actions\CreateAppointmentAction;
use App\Domain\Appointment\Actions\CancelAppointmentAction;
use App\Domain\Appointment\Actions\ConfirmAppointmentAction;
use App\Domain\Appointment\DTO\AppointmentData;
use App\Domain\Appointment\Models\Appointment;
use App\Domain\User\Models\Doctor;
use App\Infrastructure\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AppointmentController extends ApiController
{
    public function __construct(
        private AppointmentRepository $repository,
        private CreateAppointmentAction $createAction,
        private CancelAppointmentAction $cancelAction,
        private ConfirmAppointmentAction $confirmAction
    ) {}

    public function index(Request $request)
    {
        return $this->success($this->repository->getForUser($request->user()));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'doctor_id'  => 'required|exists:doctors,id',
            'patient_id' => $user->role === 'patient' ? 'nullable' : 'required|exists:patients,id',
            'date_time'  => 'required|date|after:now',
            'duration'   => 'nullable|integer|min:15',
            'type'       => 'nullable|string',
        ]);

        if ($validator->fails()) return $this->error('Validation Error', 422, $validator->errors());

        try {
            $validated = $validator->validated();
            if ($user->role === 'patient') $validated['patient_id'] = $user->patient->id;

            $data = AppointmentData::fromRequest($validated, $user->id);
            $appointment = $this->createAction->execute($data);

            return $this->success($appointment, 'Rendez-vous créé', 201);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 409);
        }
    }

    public function confirm(int $id, Request $request)
    {
        $appointment = $this->repository->findById($id);
        if (!$appointment) return $this->error('Rendez-vous non trouvé', 404);

        $this->authorize('confirm', $appointment);

        try {
            $appointment = $this->confirmAction->execute($appointment, $request->user()->id);
            return $this->success($appointment, 'Rendez-vous confirmé');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 409);
        }
    }

    public function cancel(int $id, Request $request)
    {
        $appointment = $this->repository->findById($id);
        if (!$appointment) return $this->error('Rendez-vous non trouvé', 404);

        $this->authorize('cancel', $appointment);

        try {
            $appointment = $this->cancelAction->execute($appointment, $request->reason, $request->user()->id);
            return $this->success($appointment, 'Rendez-vous annulé');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 409);
        }
    }

    public function doctors()
    {
        return $this->success(Doctor::with('user')->get());
    }

    public function show(int $id)
    {
        $appointment = $this->repository->findById($id);
        if (!$appointment) return $this->error('Rendez-vous non trouvé', 404);

        return $this->success($appointment);
    }

    public function update(Request $request, int $id)
    {
        $appointment = $this->repository->findById($id);
        if (!$appointment) return $this->error('Rendez-vous non trouvé', 404);

        $validator = Validator::make($request->all(), [
            'doctor_id'  => 'sometimes|exists:doctors,id',
            'patient_id' => 'sometimes|exists:patients,id',
            'date_time'  => 'sometimes|date|after:now',
            'status'     => 'sometimes|string|in:pending,confirmed,cancelled,completed',
            'type'       => 'sometimes|string',
        ]);

        if ($validator->fails()) return $this->error('Validation Error', 422, $validator->errors());

        $appointment->update($request->all());

        return $this->success($appointment, 'Rendez-vous mis à jour');
    }

    public function destroy(int $id)
    {
        $appointment = $this->repository->findById($id);
        if (!$appointment) return $this->error('Rendez-vous non trouvé', 404);

        $appointment->delete();

        return $this->success(null, 'Rendez-vous supprimé');
    }
}

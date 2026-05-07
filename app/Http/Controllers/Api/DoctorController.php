<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DoctorController extends ApiController
{
    public function index(): JsonResponse
    {
        $doctors = Doctor::with('user')->get();
        return $this->success($doctors);
    }

    public function stats(Request $request): JsonResponse
    {
        $doctor = $request->user()->doctor;
        if (!$doctor) return $this->error('Profil médecin non trouvé', 404);

        $stats = [
            'appointments_count' => Appointment::where('doctor_id', $doctor->id)->count(),
            'today_appointments' => Appointment::where('doctor_id', $doctor->id)->whereDate('date_time', Carbon::today())->count(),
            'patients_count' => Appointment::where('doctor_id', $doctor->id)->select('patient_id')->distinct()->count(),
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
        ];

        return $this->success($stats);
    }

    public function todayAppointments(Request $request): JsonResponse
    {
        $doctor = $request->user()->doctor;
        if (!$doctor) return $this->error('Profil médecin non trouvé', 404);

        $appointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('date_time', Carbon::today())
            ->orderBy('date_time', 'asc')
            ->get();

        return $this->success($appointments);
    }

    public function myPatients(Request $request): JsonResponse
    {
        $doctor = $request->user()->doctor;
        if (!$doctor) return $this->error('Profil médecin non trouvé', 404);

        $patients = Patient::with(['user'])
            ->whereHas('appointments', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->orWhereHas('consultations', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->get();

        return $this->success($patients);
    }

    public function establishDocument(Request $request): JsonResponse
    {
        // Placeholder implementation
        return $this->success(null, 'Document établi avec succès (simulé)');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
            'specialty' => 'required',
            'registration_number' => 'required|unique:doctors',
            'consultation_fee' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'phone' => $request->phone,
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialty' => $request->specialty,
            'registration_number' => $request->registration_number,
            'consultation_fee' => $request->consultation_fee,
        ]);

        return $this->success($doctor->load('user'), 'Médecin créé avec succès', 201);
    }

    public function show(int $id): JsonResponse
    {
        $doctor = Doctor::with(['user', 'specialite', 'departement'])->find($id);
        if (!$doctor) {
            return $this->error('Médecin non trouvé', 404);
        }
        return $this->success($doctor);
    }

    public function list(): JsonResponse
    {
        $doctors = Doctor::with('user')->get()->map(function($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'specialty' => $doctor->specialty,
            ];
        });
        return $this->success($doctors);
    }
}

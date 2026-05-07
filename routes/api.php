<?php

// routes/api.php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\SecretaryController;
use App\Http\Controllers\Api\WaitingRoomController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/ping', fn() => response()->json(['status' => 'ok', 'ip' => request()->ip(), 'time' => now()]));
Route::get('/test', fn() => response()->json(['success' => true, 'message' => 'API is working']));

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'changePassword']);
    Route::put('/profile/medical', [AuthController::class, 'updateMedicalInfo']);
    
    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    });
    
    // Patient Specific
    Route::prefix('patient')->middleware('role:patient')->group(function () {
        Route::get('/medical-record', [PatientController::class, 'medicalRecord']);
        Route::put('/medical-record', [PatientController::class, 'updateMedicalRecord']);
        Route::get('/prescriptions', [PatientController::class, 'prescriptions']);
        Route::get('/invoices', [PatientController::class, 'invoices']);
        Route::get('/consultations', [PatientController::class, 'consultations']);
    });

    // Appointments (shared)
    Route::apiResource('appointments', AppointmentController::class)->only(['index', 'store', 'show']);
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
    Route::post('/appointments/{id}/confirm', [AppointmentController::class, 'confirm']);
    
    // Doctors list (shared)
    Route::get('/doctors', [DoctorController::class, 'list']);
    
    // Admin routes
    Route::prefix('admin')->middleware('role:chef_medecine')->group(function () {
        Route::get('/stats', [AdminDashboardController::class, 'stats']);
        Route::get('/users/recent', [UserController::class, 'recent']);
        Route::apiResource('users', UserController::class);
        Route::apiResource('doctors', DoctorController::class);
        Route::get('/reports', [ReportController::class, 'index']);
    });
    
    // Doctor routes
    Route::prefix('doctor')->middleware('role:doctor')->group(function () {
        Route::get('/stats', [DoctorController::class, 'stats']);
        Route::get('/appointments/today', [DoctorController::class, 'todayAppointments']);
        Route::get('/waiting-room', [WaitingRoomController::class, 'doctorIndex']);
        Route::post('/waiting-room/call-next', [WaitingRoomController::class, 'callNext']);
        Route::get('/consultations', [ConsultationController::class, 'doctorIndex']);
        Route::post('/consultations/start', [ConsultationController::class, 'start']);
        Route::get('/patients', [DoctorController::class, 'myPatients']);
        Route::post('/establish-document', [DoctorController::class, 'establishDocument']);
    });
    
    // Secretary routes
    Route::prefix('secretaire')->middleware('role:secretaire')->name('api.secretaire.')->group(function () {
        Route::get('/stats', [SecretaryController::class, 'stats']);
        Route::get('/waiting-room', [WaitingRoomController::class, 'secretaryIndex']);
        Route::post('/waiting-room/add', [WaitingRoomController::class, 'add']);
        Route::delete('/waiting-room/{id}/remove', [WaitingRoomController::class, 'remove']);
        Route::apiResource('patients', PatientController::class);
        Route::apiResource('appointments', AppointmentController::class);
        Route::apiResource('invoices', InvoiceController::class);
    });
});
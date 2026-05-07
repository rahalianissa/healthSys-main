<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ComptabiliteController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\SecretaryDashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

require __DIR__.'/auth.php';

// ==================== ROUTES PUBLIQUES ====================

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/profile/remove-avatar', [ProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');

// ==================== ROUTES PROTÉGÉES (TOUS LES UTILISATEURS) ====================
Route::middleware(['auth'])->group(function () {
    
    // Routes notifications globales
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all');
    
    // Dashboard redirection selon rôle
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role == 'patient') return redirect()->route('patient.dashboard');
        elseif ($user->role == 'doctor') return redirect()->route('doctor.dashboard');
        elseif ($user->role == 'secretaire') return redirect()->route('secretaire.dashboard');
        elseif ($user->role == 'chef_medecine') return redirect()->route('admin.dashboard');
        return view('dashboard');
    })->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', function() {
        return view('settings', ['user' => auth()->user()]);
    })->name('settings');
    Route::post('/settings/save', function (Request $request) {
        $user = auth()->user();
        return response()->json(['success' => true]);
    })->name('settings.save');
    Route::post('/settings/notifications', function (Request $request) {
        auth()->user()->update(['notification_preference' => $request->enabled]);
        return response()->json(['success' => true]);
    })->name('settings.notifications');
    
    // ==================== ROUTES FACTURES (COMPLÈTES) ====================
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    
    // Routes de paiement
    Route::get('/invoices/{invoice}/pay', [InvoiceController::class, 'paymentPage'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/process-payment', [InvoiceController::class, 'processPayment'])->name('invoices.processPayment');
    
    // Routes d'impression et PDF
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'printInvoice'])->name('invoices.print');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdfInvoice'])->name('invoices.pdf');
    
    // Routes pour les réclamations d'assurance
    Route::get('/claims/cnam', [InvoiceController::class, 'cnamClaims'])->name('claims.cnam');
    Route::get('/claims/mutuelle', [InvoiceController::class, 'mutuelleClaims'])->name('claims.mutuelle');
    
    // Routes de recherche
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
    
    // Routes de paiement externe
    Route::get('/payment/{invoice}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/{invoice}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{invoice}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    
    // Routes consultations
    Route::get('/consultations/{consultation}', [ConsultationController::class, 'show'])->name('consultations.show');
    Route::get('/consultations/{consultation}/details', [ConsultationController::class, 'details'])->name('consultations.details');
    
    // Routes prescriptions
    Route::resource('prescriptions', PrescriptionController::class);
    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'pdf'])->name('prescriptions.pdf');
    Route::get('/prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescriptions.print');
    
    // Routes recherche globale
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
    
    // ==================== ROUTES CALENDRIER GLOBAL ====================
    Route::get('/api/events', function () {
        $user = auth()->user();
        
        if ($user->role == 'doctor' && $user->doctor) {
            $appointments = App\Models\Appointment::with(['patient.user'])
                ->where('doctor_id', $user->doctor->id)
                ->get();
        } elseif ($user->role == 'secretaire') {
            $appointments = App\Models\Appointment::with(['patient.user', 'doctor.user'])->get();
        } elseif ($user->role == 'patient' && $user->patient) {
            $appointments = App\Models\Appointment::with(['doctor.user'])
                ->where('patient_id', $user->patient->id)
                ->get();
        } else {
            return response()->json([]);
        }
        
        $colors = [
            'pending' => '#ffc107',
            'confirmed' => '#28a745', 
            'cancelled' => '#dc3545',
            'completed' => '#17a2b8'
        ];
        
        return response()->json($appointments->map(function ($app) use ($colors) {
            return [
                'id' => $app->id,
                'title' => $app->patient->user->name ?? 'Patient',
                'start' => $app->date_time,
                'color' => $colors[$app->status] ?? '#6c757d',
                'url' => '/secretaire/appointments/' . $app->id,
            ];
        }));
    });
});

// ==================== ROUTES MÉDECIN ====================
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/waiting-room', [WaitingRoomController::class, 'doctorIndex'])->name('waiting-room');
    Route::post('/consultation/start/{waitingRoom}', [WaitingRoomController::class, 'startConsultation'])->name('consultation.start');
    Route::post('/consultation/complete/{waitingRoom}', [WaitingRoomController::class, 'complete'])->name('consultation.complete');
    Route::get('/consultations', [ConsultationController::class, 'doctorConsultations'])->name('consultations');
    Route::get('/consultations/create', [ConsultationController::class, 'createConsultation'])->name('consultations.create');
    Route::post('/consultations', [ConsultationController::class, 'storeConsultation'])->name('consultations.store');
    Route::get('/consultations/{id}', [ConsultationController::class, 'doctorShowConsultation'])->name('consultations.show');
    Route::get('/history', [ConsultationController::class, 'visitHistory'])->name('history');
    Route::get('/establish-document', [DocumentController::class, 'establish'])->name('establish-document');
    Route::post('/establish-document/prescription', [DocumentController::class, 'storePrescription'])->name('store-prescription');
    Route::post('/establish-document/certificate', [DocumentController::class, 'storeCertificate'])->name('store-certificate');
    Route::post('/establish-document/report', [DocumentController::class, 'storeReport'])->name('store-report');
    Route::get('/patients', [DoctorController::class, 'myPatients'])->name('patients');
    Route::get('/patients/{patient}', [DoctorController::class, 'showPatient'])->name('patients.show');
    Route::get('/appointments/{id}', [AppointmentController::class, 'doctorShow'])->name('appointments.show');
    
    // ✅ ROUTE MANQUANTE - Création de rendez-vous par le médecin
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    
    Route::get('/notifications', [DoctorController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all', [DoctorController::class, 'markAllNotifications'])->name('notifications.mark-all');
    Route::post('/notifications/{id}/mark-read', [DoctorController::class, 'markNotificationRead'])->name('notifications.mark-read');
    
    // CALENDRIER MEDECIN
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
});

// ==================== ROUTES SECRÉTAIRE ====================
Route::middleware(['auth', 'role:secretaire,chef_medecine'])->prefix('secretaire')->name('secretaire.')->group(function () {
    
    Route::get('/dashboard', [SecretaryDashboardController::class, 'index'])->name('dashboard');
    Route::get('/comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite');
    Route::get('/paiements', [ComptabiliteController::class, 'paiements'])->name('paiements');
    Route::get('/facture/create', [ComptabiliteController::class, 'createFacture'])->name('facture.create');
    Route::post('/facture', [InvoiceController::class, 'store'])->name('facture.store');
    Route::get('/claims/cnam', [InvoiceController::class, 'cnamClaims'])->name('claims.cnam');
    Route::get('/claims/mutuelle', [InvoiceController::class, 'mutuelleClaims'])->name('claims.mutuelle');
    
    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    
    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/appointments/{id}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::get('/appointments/{id}/cancel', [AppointmentController::class, 'cancelBySecretary'])->name('appointments.cancel');
    
    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');
    
    // Waiting Room
    Route::get('/waiting-room', [WaitingRoomController::class, 'secretaireIndex'])->name('waiting-room');
    Route::post('/waiting-room/add', [WaitingRoomController::class, 'add'])->name('waiting-room.add');
    Route::delete('/waiting-room/{waitingRoom}/remove', [WaitingRoomController::class, 'remove'])->name('waiting-room.remove');
    
    // Notifications
    Route::get('/notifications', [SecretaryController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all', [SecretaryController::class, 'markAllNotifications'])->name('notifications.mark-all');
    Route::post('/notifications/{id}/mark-read', [SecretaryController::class, 'markNotificationRead'])->name('notifications.mark-read');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
});

// ==================== ROUTES ADMIN ====================
Route::middleware(['auth', 'role:chef_medecine'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('doctors', DoctorController::class);
    Route::resource('secretaries', SecretaryController::class);
    Route::resource('specialites', SpecialiteController::class);
    Route::resource('departements', DepartementController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/yearly', [ReportController::class, 'yearly'])->name('reports.yearly');
    Route::get('/export/patients', [ExportController::class, 'patients'])->name('export.patients');
    Route::get('/export/appointments', [ExportController::class, 'appointments'])->name('export.appointments');
    Route::get('/export/invoices', [ExportController::class, 'invoices'])->name('export.invoices');
    Route::get('/consultations/{consultation}/details', [ConsultationController::class, 'details'])->name('consultations.details');
    
    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
});

// ==================== ROUTES PATIENT ====================
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments');
    Route::get('/appointments/{id}', [AppointmentController::class, 'patientShow'])->name('appointments.show');
    Route::post('/appointments/{id}/confirm', [AppointmentController::class, 'confirmOnline'])->name('appointments.confirm');
    Route::post('/appointments/book', [AppointmentController::class, 'bookOnline'])->name('book');
    Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancelOnline'])->name('cancel');
    Route::get('/medical-record', [ConsultationController::class, 'patientMedicalRecord'])->name('medical-record');
    Route::get('/prescriptions', [PrescriptionController::class, 'patientPrescriptions'])->name('prescriptions');
    Route::get('/invoices', [InvoiceController::class, 'patientInvoices'])->name('invoices');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/pay', [InvoiceController::class, 'paymentPage'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/process-payment', [InvoiceController::class, 'processPayment'])->name('invoices.processPayment');
    Route::get('/notifications', [PatientController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all', [PatientController::class, 'markAllNotifications'])->name('notifications.mark-all');
    Route::post('/notifications/{id}/mark-read', [PatientController::class, 'markNotificationRead'])->name('notifications.mark-read');
    
    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
});

// ==================== ROUTES MANQUANTES ====================

// Route pour les paramètres (settings)
Route::middleware(['auth'])->get('/settings', function () {
    return view('settings');
})->name('settings');

Route::middleware(['auth'])->post('/settings/save', function (Request $request) {
    $user = auth()->user();
    if ($request->has('language')) {
        $user->update(['language' => $request->language]);
        session()->put('locale', $request->language);
    }
    if ($request->has('notification_enabled')) {
        $user->update(['notification_enabled' => $request->notification_enabled]);
    }
    return response()->json(['success' => true]);
})->name('settings.save');

// Route pour les notifications globales (marquer toutes comme lues)
Route::middleware(['auth'])->post('/notifications/mark-all-global', function () {
    auth()->user()->unreadNotifications->markAsRead();
    if (request()->wantsJson()) {
        return response()->json(['success' => true, 'message' => 'Toutes les notifications ont été marquées comme lues']);
    }
    return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
})->name('notifications.mark-all-global');
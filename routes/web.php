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
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ========== INCLURE LES ROUTES D'AUTHENTIFICATION ==========
require __DIR__.'/auth.php';

// ========== ROUTE POUR CHANGER LA LANGUE ==========
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'ar', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// ========== PAGE D'ACCUEIL ==========
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ========== ROUTES POUR TOUS LES UTILISATEURS AUTHENTIFIÉS ==========
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role == 'patient') {
            return view('patient.dashboard');
        } elseif ($user->role == 'doctor') {
            return view('doctor.dashboard');
        } elseif ($user->role == 'secretaire') {
            return view('secretaire.dashboard');
        } elseif ($user->role == 'chef_medecine') {
            return view('admin.dashboard');
        }
        
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    
    Route::post('/settings/notifications', function (Request $request) {
        $user = auth()->user();
        $user->notification_preference = $request->enabled;
        $user->save();
        return response()->json(['success' => true]);
    })->name('settings.notifications');
});

// ========== ROUTES POUR LES PATIENTS ==========
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', function () {
        return view('patient.dashboard');
    })->name('dashboard');
    
    Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments');
    Route::post('/appointments/book', [AppointmentController::class, 'bookOnline'])->name('book');
    Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancelOnline'])->name('cancel');
    Route::get('/medical-record', [ConsultationController::class, 'patientMedicalRecord'])->name('medical-record');
    Route::get('/prescriptions', [PrescriptionController::class, 'patientPrescriptions'])->name('prescriptions');
    Route::get('/invoices', [InvoiceController::class, 'patientInvoices'])->name('invoices');
});

// ========== ROUTES POUR LES MÉDECINS ==========
Route::middleware(['auth', 'role:doctor,chef_medecine'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('doctor.dashboard');
    })->name('dashboard');
    
    Route::get('/waiting-room', [WaitingRoomController::class, 'doctorIndex'])->name('waiting-room');
    Route::post('/consultation/start/{waitingRoom}', [WaitingRoomController::class, 'startConsultation'])->name('consultation.start');
    Route::get('/consultations', [ConsultationController::class, 'doctorConsultations'])->name('consultations');
    Route::get('/consultations/create', [ConsultationController::class, 'createConsultation'])->name('consultations.create');
    Route::post('/consultations', [ConsultationController::class, 'storeConsultation'])->name('consultations.store');
    Route::get('/history', [ConsultationController::class, 'visitHistory'])->name('history');
    Route::get('/establish-document', [DocumentController::class, 'establish'])->name('establish-document');
    Route::post('/establish-document/prescription', [DocumentController::class, 'storePrescription'])->name('store-prescription');
    Route::post('/establish-document/certificate', [DocumentController::class, 'storeCertificate'])->name('store-certificate');
    Route::post('/establish-document/report', [DocumentController::class, 'storeReport'])->name('store-report');
    Route::get('/patients', [DoctorController::class, 'myPatients'])->name('patients');
    Route::get('/patients/{patient}', [DoctorController::class, 'showPatient'])->name('patients.show');
    Route::get('/notifications', [DoctorController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all', [DoctorController::class, 'markAllNotifications'])->name('notifications.mark-all');
    Route::post('/notifications/{id}/mark-read', [DoctorController::class, 'markNotificationRead'])->name('notifications.mark-read');
});

// ========== ROUTES POUR LES SECRÉTAIRES ==========
Route::middleware(['auth', 'role:secretaire,chef_medecine'])->prefix('secretaire')->name('secretaire.')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretaire.dashboard');
    })->name('dashboard');
    Route::get('/comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite');
    Route::get('/paiements', [ComptabiliteController::class, 'paiements'])->name('paiements');
    Route::get('/facture/create', [ComptabiliteController::class, 'createFacture'])->name('facture.create');
    Route::post('/facture', [ComptabiliteController::class, 'storeFacture'])->name('facture.store');
    Route::resource('appointments', AppointmentController::class);
    Route::resource('patients', PatientController::class);
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');
    Route::get('/waiting-room', [WaitingRoomController::class, 'secretaireIndex'])->name('waiting-room');
    Route::post('/waiting-room/add', [WaitingRoomController::class, 'add'])->name('waiting-room.add');
    Route::delete('/waiting-room/{waitingRoom}/remove', [WaitingRoomController::class, 'remove'])->name('waiting-room.remove');
});

// ========== ROUTES POUR LE CHEF DE MÉDECINE (ADMIN) ==========
Route::middleware(['auth', 'role:chef_medecine'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
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
});

// ========== ROUTES POUR LES CONSULTATIONS ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/consultations/{consultation}', [ConsultationController::class, 'show'])->name('consultations.show');
    Route::get('/consultations/{consultation}/details', [ConsultationController::class, 'details'])->name('consultations.details');
});

// ========== ROUTES POUR LES FACTURES ==========
Route::middleware(['auth'])->group(function () {
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pay', [InvoiceController::class, 'paymentPage'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/process-payment', [InvoiceController::class, 'processPayment'])->name('invoices.processPayment');
    Route::get('/payment/{invoice}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/{invoice}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{invoice}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// ========== ROUTES PATIENT POUR FACTURES ==========
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'patientInvoices'])->name('invoices');
});

// ========== ROUTES DE RECHERCHE ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
});

// ========== ROUTE DE TEST ==========
Route::post('/test-upload', function(Request $request) {
    if ($request->hasFile('test_image')) {
        $path = $request->file('test_image')->store('test', 'public');
        return "File uploaded to: " . $path;
    }
    return "No file uploaded";
})->middleware('auth');
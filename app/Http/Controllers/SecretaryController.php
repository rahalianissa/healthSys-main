<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departement;
use App\Notifications\SystemNotification; // ⚠️ AJOUTER CETTE LIGNE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SecretaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:chef_medecine')->except(['dashboard', 'notifications', 'markAllNotifications', 'markNotificationRead']);
    }

    public function dashboard()
    {
        return view('secretaire.dashboard');
    }

    public function index()
    {
        $secretaries = User::where('role', 'secretaire')->with('departement')->get();
        return view('admin.secretaries.index', compact('secretaries'));
    }

    public function create()
    {
        $departements = Departement::all();
        return view('admin.secretaries.create', compact('departements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'departement_id' => 'required|exists:departements,id',
        ]);

        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'secretaire',
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'departement_id' => $request->departement_id,
        ]);

        // Envoyer email avec mot de passe
        Mail::send('emails.secretary-welcome', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $password,
            'loginUrl' => route('login')
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('Bienvenue sur HealthSys - Vos identifiants');
        });

        return redirect()->route('admin.secretaries.index')->with('success', '✅ Secrétaire ajoutée ! Email envoyé.');
    }

    public function edit(User $secretary)
    {
        if ($secretary->role != 'secretaire') abort(404);
        $departements = Departement::all();
        return view('admin.secretaries.edit', compact('secretary', 'departements'));
    }

    public function update(Request $request, User $secretary)
    {
        if ($secretary->role != 'secretaire') abort(404);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $secretary->id,
            'phone' => 'required',
            'departement_id' => 'required|exists:departements,id',
        ]);

        $secretary->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'departement_id' => $request->departement_id,
        ]);

        if ($request->filled('password')) {
            $secretary->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.secretaries.index')->with('success', 'Secrétaire modifiée');
    }

    public function destroy(User $secretary)
    {
        if ($secretary->role != 'secretaire') abort(404);
        $secretary->delete();
        return redirect()->route('admin.secretaries.index')->with('success', 'Secrétaire supprimée');
    }

    // ==================== 🔔 NOTIFICATIONS SECRÉTAIRE ====================
    
    /**
     * Afficher toutes les notifications de la secrétaire
     */
    public function notifications()
    {
        return view('secretaire.notifications');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Marquer une notification spécifique comme lue
     */
    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }
}
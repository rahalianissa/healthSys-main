<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ];

        if ($user->role == 'doctor' && $user->doctor) {
            $rules['specialty'] = 'nullable|string';
            $rules['registration_number'] = 'nullable|string';
            $rules['consultation_fee'] = 'nullable|numeric';
        }

        $request->validate($rules);

        // ================= MISE À JOUR TABLE users =================
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
        ]);

        // ================= GESTION AVATAR =================
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar) {
                $oldPath = public_path('assets/img/avatars/' . $user->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Générer un nom unique pour la nouvelle image
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Créer le dossier s'il n'existe pas
            if (!file_exists(public_path('assets/img/avatars'))) {
                mkdir(public_path('assets/img/avatars'), 0777, true);
            }
            
            // Déplacer l'image vers le dossier
            $file->move(public_path('assets/img/avatars'), $filename);
            
            // Sauvegarder le nom dans la base de données
            $user->avatar = $filename;
            $user->save();
        }

        // ================= MISE À JOUR TABLE doctors =================
        if ($user->role == 'doctor' && $user->doctor) {
            $doctor = $user->doctor;
            $doctor->specialty = $request->specialty ?? $doctor->specialty;
            $doctor->registration_number = $request->registration_number ?? $doctor->registration_number;
            $doctor->consultation_fee = $request->consultation_fee ?? $doctor->consultation_fee;
            $doctor->save();
        }

        // ================= CHANGER LE MOT DE PASSE =================
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès');
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends ApiController
{
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Identifiants invalides', 401);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'avatar' => $user->avatar ?? null,
            ],
            'token' => $token,
        ], 'Connexion réussie');
    }

    /**
     * Register new patient
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
            'phone' => 'required|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'phone' => $request->phone,
        ]);

        // Create patient record
        $patient = Patient::create([
            'user_id' => $user->id,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'blood_type' => $request->blood_type,
            'allergies' => $request->allergies,
            'medical_history' => $request->medical_history,
        ]);

        $token = $user->createToken('mobile_app')->plainTextToken;

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'avatar' => $user->avatar ?? null,
                'patient' => $patient,
            ],
            'token' => $token,
        ], 'Inscription réussie', 201);
    }

    /**
     * Get authenticated user profile (alias for profile)
     */
    public function user(Request $request)
    {
        return $this->profile($request);
    }

    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Load relationships based on role
        if ($user->role === 'patient') {
            $user->load('patient');
        } elseif ($user->role === 'doctor') {
            $user->load('doctor');
        }
        
        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'avatar' => $user->avatar ?? null,
            'patient' => $user->patient,
            'doctor' => $user->doctor,
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'nullable|string',
            'language' => 'sometimes|string|in:fr,en,ar',
            'notification_enabled' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user->update($request->only(['name', 'phone', 'avatar', 'language', 'notification_enabled']));

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ], 'Profil mis à jour');
    }

    /**
     * Update patient medical information
     */
    public function updateMedicalInfo(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'patient') {
            return $this->error('Seuls les patients peuvent modifier leurs informations médicales', 403);
        }
        
        $validator = Validator::make($request->all(), [
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
            'insurance_number' => 'nullable|string',
            'insurance_company' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $patient = $user->patient;
        if (!$patient) {
            $patient = Patient::create(['user_id' => $user->id]);
        }
        
        $patient->update($request->only([
            'birth_date', 'address', 'blood_type', 'weight', 'height',
            'allergies', 'medical_history', 'emergency_contact', 'emergency_phone',
            'insurance_number', 'insurance_company'
        ]));

        return $this->success($patient, 'Informations médicales mises à jour');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Mot de passe actuel incorrect', 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Revoke all tokens except current
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return $this->success(null, 'Mot de passe modifié avec succès');
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Déconnexion réussie');
    }

    /**
     * Logout from all devices
     */
    public function logoutAllDevices(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success(null, 'Déconnecté de tous les appareils');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        
        // Revoke all tokens
        $user->tokens()->delete();
        
        // Delete user (cascade will handle relations)
        $user->delete();
        
        return $this->success(null, 'Compte supprimé avec succès');
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        // Generate reset token
        $token = \Str::random(64);
        
        // Store in password_resets table
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );
        
        // TODO: Send email with reset link
        // Mail::send(...);
        
        return $this->success(null, 'Email de réinitialisation envoyé');
    }

    /**
     * Reset password with token
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $reset = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return $this->error('Token invalide ou expiré', 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Delete reset record
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Revoke all tokens
        $user->tokens()->delete();

        return $this->success(null, 'Mot de passe réinitialisé avec succès');
    }
}
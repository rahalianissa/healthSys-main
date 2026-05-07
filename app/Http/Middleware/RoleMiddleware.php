<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Non authentifié.'], 401);
            }
            return redirect('/login');
        }
        
        $userRole = auth()->user()->role;
        
        // Vérification insensible à la casse et support des alias
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles[] = strtolower($role);
            // Ajouter des alias communs
            if ($role === 'secretaire') {
                $allowedRoles[] = 'secretary';
                $allowedRoles[] = 'secretaire_medical';
            }
            if ($role === 'chef_medecine') {
                $allowedRoles[] = 'admin';
                $allowedRoles[] = 'chef_de_service';
                $allowedRoles[] = 'administrator';
            }
            if ($role === 'doctor') {
                $allowedRoles[] = 'medecin';
                $allowedRoles[] = 'docteur';
            }
            if ($role === 'patient') {
                $allowedRoles[] = 'patient_consultation';
            }
        }
        
        $hasRole = in_array(strtolower($userRole), $allowedRoles);
        
        if (!$hasRole) {
            if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Accès non autorisé.',
                    'required_roles' => $roles,
                    'your_role' => $userRole
                ], 403);
            }
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.');
        }
        
        return $next($request);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $fillable = ['nom', 'description'];

    /**
     * Relation avec les secrétaires (users avec role 'secretaire')
     */
    public function secretaries()
    {
        return $this->hasMany(User::class, 'departement_id')->where('role', 'secretaire');
    }

    /**
     * Relation avec tous les utilisateurs du département
     */
    public function users()
    {
        return $this->hasMany(User::class, 'departement_id');
    }
}
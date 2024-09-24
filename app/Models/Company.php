<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont massivement assignables.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'structure',
        'address',
        'sector',
        'contact_name',
        'contact_email',
        'contact_phone',
        'tva_number',    // Numéro de TVA
        'siret',         // SIRET
        'siren',         // SIREN
    ];

    /**
     * Relation avec les utilisateurs (employés de la société).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation avec les matériels.
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}

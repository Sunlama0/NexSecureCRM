<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'device_name',
        'device_identification',
        'serial_number',
        'description',
        'acquisition_date',
        'supplier',
        'cost',
        'category_id',
        'company_id',
        'status',
        'out_of_stock',
        'collaborator_firstname',
        'collaborator_lastname',
        'collaborator_position',
        'assigned_date',
        'assigned_to',
        'supplier_id',
        'device_identifier_id',
    ];

    /**
     * Relation avec la société.
     * Un matériel appartient à une société.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relation avec la catégorie.
     * Un matériel appartient à une catégorie.
     */
    public function category()
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    /**
     * Génération automatique d'un identifiant unique pour le matériel (si nécessaire).
     */
    public static function boot()
    {
        parent::boot();

        // Génération d'un identifiant unique pour le matériel lors de sa création
        static::creating(function ($material) {
            $material->reference = 'M-' . strtoupper(uniqid());
        });
    }

    // Relation avec MaterialHistory
    public function histories()
    {
        return $this->hasMany(MaterialHistory::class);
    }

    public function deviceIdentifier()
    {
        return $this->belongsTo(DeviceIdentifier::class);
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function MaterialCategory()
    {
        return $this->belongsTo(MaterialCategory::class);
    }

}

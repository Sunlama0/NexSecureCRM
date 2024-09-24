<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'status',
        'notes',
        'changed_by',
    ];

    // Relation avec le matériel
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

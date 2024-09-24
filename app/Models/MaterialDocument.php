<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'document_path',
        'uploaded_by',
    ];

    // Relation avec le modèle Material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

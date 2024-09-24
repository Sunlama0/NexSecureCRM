<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'company_id'];

    /**
     * Relation avec la société.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relation avec les sous-catégories de matériels (si tu as un système de sous-catégories).
     */
    public function subcategories()
    {
        return $this->hasMany(MaterialSubCategory::class);
    }
}

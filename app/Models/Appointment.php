<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Ajout des champs autorisés pour l'assignation massive
    protected $fillable = [
        'title',
        'participants',
        'date',
        'start_time',
        'end_time',
        'location',
        'description',
        'category',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'address', 'company', 'company_id'];

    // Relation : un client appartient à une société
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

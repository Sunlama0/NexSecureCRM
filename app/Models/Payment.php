<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'amount', 'payment_method', 'payment_date', 'company_id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Relation avec la société
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}


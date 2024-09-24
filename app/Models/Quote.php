<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'user_id', 'quote_number', 'quote_date', 'expiration_date', 'subject', 'subtotal', 'discount', 'tax', 'total'];

    // Relation avec les clients
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec les utilisateurs (vendeurs)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les articles du devis
    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    // Relation avec la société
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}

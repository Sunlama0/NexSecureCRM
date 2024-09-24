<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Le vendeur (l'utilisateur qui crée le devis)
            $table->string('quote_number')->unique(); // Devis#
            $table->date('quote_date'); // Date du devis
            $table->date('expiration_date'); // Date d’expiration
            $table->string('subject'); // Objet du devis
            $table->decimal('subtotal', 10, 2); // Sous-total
            $table->decimal('discount', 10, 2)->default(0); // Remise
            $table->decimal('tax', 10, 2); // Taxe
            $table->decimal('total', 10, 2); // Total (€)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}

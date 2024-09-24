<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('subject');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_tax', 15, 2);
            $table->decimal('total_discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('status')->default('Brouillon'); // Statut : Brouillon, Expiré, Envoyé, Payé
            $table->unsignedBigInteger('user_id'); // L'utilisateur qui a créé la facture
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}

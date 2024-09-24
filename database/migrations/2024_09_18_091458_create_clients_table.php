<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // Prénom
            $table->string('last_name');  // Nom
            $table->string('email')->unique(); // Email unique
            $table->string('phone'); // Numéro de téléphone
            $table->string('address'); // Adresse
            $table->string('company')->nullable(); // Entreprise (optionnelle)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}

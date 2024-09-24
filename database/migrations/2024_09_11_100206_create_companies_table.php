<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();  // ID unique pour la société
            $table->string('name');  // Nom de la société
            $table->string('structure');  // Structure de l'entreprise (ex : SARL, SAS, etc.)
            $table->string('address');  // Adresse de la société
            $table->string('sector');  // Secteur d'activité
            $table->string('contact_name');  // Nom du contact principal
            $table->string('contact_email')->unique();  // Email du contact principal
            $table->string('contact_phone');  // Téléphone du contact principal
            $table->timestamps();  // Champs timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

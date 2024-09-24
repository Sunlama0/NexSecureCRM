<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->string('collaborator_firstname')->nullable(); // Prénom du collaborateur
            $table->string('collaborator_lastname')->nullable(); // Nom du collaborateur
            $table->string('collaborator_position')->nullable(); // Poste du collaborateur
            $table->date('assigned_date')->nullable(); // Date d'attribution
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            //
        });
    }
};

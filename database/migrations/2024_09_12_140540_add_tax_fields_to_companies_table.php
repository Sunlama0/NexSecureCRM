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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('tva_number')->nullable(); // Numéro de TVA
            $table->string('siret')->nullable();      // SIRET
            $table->string('siren')->nullable();      // SIREN
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['tva_number', 'siret', 'siren']);
        });
    }

};

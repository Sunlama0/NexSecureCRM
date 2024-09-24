<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoAndPositionToCompaniesAndUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ajouter le champ logo dans la table companies
        Schema::table('companies', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('siren'); // Ajout du champ logo après le champ siren
        });

        // Ajouter le champ position dans la table users
        Schema::table('users', function (Blueprint $table) {
            $table->string('position')->nullable()->after('email'); // Ajout du champ position après le champ email
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Suppression du champ logo de la table companies
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

        // Suppression du champ position de la table users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
}

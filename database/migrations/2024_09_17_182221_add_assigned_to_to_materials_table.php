<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedToToMaterialsTable extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Ajouter la colonne assigned_to pour stocker le collaborateur à qui le matériel est attribué
            $table->string('assigned_to')->nullable()->after('status');
        });
    }

    /**
     * Inverse les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Supprimer les colonnes assigned_to, collaborator_position et assigned_date si elles existent
            $table->dropColumn('assigned_to');
        });
    }
}

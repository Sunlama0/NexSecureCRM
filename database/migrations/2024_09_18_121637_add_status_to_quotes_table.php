<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToQuotesTable extends Migration
{
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Ajout de la colonne status avec Brouillon par défaut
            $table->string('status')->default('Brouillon')->after('total');
        });
    }

    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Suppression de la colonne status
            $table->dropColumn('status');
        });
    }
}

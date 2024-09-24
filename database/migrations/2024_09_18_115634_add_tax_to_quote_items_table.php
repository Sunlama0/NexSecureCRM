<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxToQuoteItemsTable extends Migration
{
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            // Ajout de la colonne 'tax' avec une valeur par défaut de 0
            $table->decimal('tax', 10, 2)->default(0)->after('rate');
        });
    }

    public function down()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            // Supprimer la colonne 'tax' si la migration est annulée
            $table->dropColumn('tax');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToQuoteItemsTable extends Migration
{
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            // Ajout de la colonne 'discount' avec une valeur par défaut de 0
            $table->decimal('discount', 10, 2)->default(0)->after('tax');
        });
    }

    public function down()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            // Supprimer la colonne 'discount' si la migration est annulée
            $table->dropColumn('discount');
        });
    }
}

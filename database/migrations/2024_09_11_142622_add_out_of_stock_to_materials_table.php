<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutOfStockToMaterialsTable extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Ajouter la colonne out_of_stock, qui sera un boolean
            $table->boolean('out_of_stock')->default(false)->after('status');
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
            // Supprimer la colonne out_of_stock si elle existe
            $table->dropColumn('out_of_stock');
        });
    }
}

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
            // $table->unsignedBigInteger('supplier_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('device_identifier_id')->nullable()->after('supplier_id');

            // Vous pouvez ajouter les clés étrangères si nécessaire
            // $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('device_identifier_id')->references('id')->on('device_identifiers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            // $table->dropForeign(['supplier_id']);
            $table->dropForeign(['device_identifier_id']);
            $table->dropColumn(['device_identifier_id']);
        });
    }

};

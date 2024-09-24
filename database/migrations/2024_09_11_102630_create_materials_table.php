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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // Société liée
            $table->foreignId('category_id')->constrained('material_categories')->onDelete('cascade'); // Catégorie liée
            $table->string('reference');
            $table->string('description');
            $table->string('serial_number');
            $table->date('acquisition_date');
            $table->string('supplier')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

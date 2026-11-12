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
    Schema::create('servicio_tour', function (Blueprint $table) {
        $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
        $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
        $table->primary(['servicio_id', 'tour_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_tour');
    }
};

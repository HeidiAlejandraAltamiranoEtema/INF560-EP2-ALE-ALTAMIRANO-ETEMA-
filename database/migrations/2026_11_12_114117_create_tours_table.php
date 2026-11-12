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
    Schema::create('tours', function (Blueprint $table) {
        $table->id();
        $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
        $table->foreignId('guia_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('titulo');
        $table->string('slug')->unique();
        $table->text('descripcion');
        $table->decimal('precio', 10, 2);
        $table->unsignedInteger('duracion_horas');
        $table->unsignedInteger('cupo_maximo');
        $table->dateTime('fecha_salida');
        $table->string('imagen')->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};

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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');
            $table->string('nombre', 150);
            $table->string('telefono', 20);
            $table->string('correo', 150)->nullable();
            $table->string('tipo_servicio', 100);
            $table->text('descripcion')->nullable();
            $table->date('fecha_requerida')->nullable();
            $table->string('origen', 255)->nullable();
            $table->string('destino', 255)->nullable();
            $table->integer('personas')->nullable();
            $table->string('estado', 50)->default('Pendiente');
            $table->text('respuesta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};

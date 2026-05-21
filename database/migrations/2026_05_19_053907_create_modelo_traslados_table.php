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
        Schema::create('modelo_traslados', function (Blueprint $table) {
            $table->id('id_modelo_servicio');
            $table->decimal('b0', 10, 2)->default(1000.00);
            $table->decimal('b_distancia', 10, 2)->default(25.00);
            $table->decimal('b_horas', 10, 2)->default(150.00);
            $table->decimal('b_oxigeno', 10, 2)->default(5.00);
            $table->decimal('b_padecimiento', 10, 2)->default(300.00);
            $table->decimal('b_ambulancia', 10, 2)->default(500.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelo_traslados');
    }
};

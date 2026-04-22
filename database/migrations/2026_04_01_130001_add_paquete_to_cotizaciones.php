<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ambulancia')->nullable()->after('nombre_paciente');
            $table->decimal('horas_servicio', 5, 2)->nullable()->after('id_ambulancia');
            $table->json('paramedicos_ids')->nullable()->after('horas_servicio');
            $table->json('insumos_seleccionados')->nullable()->after('paramedicos_ids');
            $table->decimal('costo_km_unitario', 8, 2)->nullable()->after('insumos_seleccionados');
            $table->decimal('costo_ambulancia', 10, 2)->nullable()->after('costo_km_unitario');
            $table->decimal('costo_paramedicos', 10, 2)->nullable()->after('costo_ambulancia');
            $table->decimal('costo_insumos', 10, 2)->nullable()->after('costo_paramedicos');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn([
                'id_ambulancia', 'horas_servicio', 'paramedicos_ids',
                'insumos_seleccionados', 'costo_km_unitario',
                'costo_ambulancia', 'costo_paramedicos', 'costo_insumos',
            ]);
        });
    }
};

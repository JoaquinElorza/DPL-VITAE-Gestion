<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->decimal('km_distancia', 8, 2)->nullable()->after('lng_destino');
            $table->decimal('costo', 10, 2)->nullable()->after('km_distancia');
            $table->text('incluye')->nullable()->after('costo');
            $table->text('padecimientos_paciente')->nullable()->after('incluye');
            $table->string('nombre_paciente', 200)->nullable()->after('padecimientos_paciente');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['km_distancia', 'costo', 'incluye', 'padecimientos_paciente', 'nombre_paciente']);
        });
    }
};

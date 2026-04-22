<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->decimal('costo_km', 8, 2)->default(25.00)->after('direccion');
        });

        Schema::table('tipo_ambulancia', function (Blueprint $table) {
            $table->decimal('costo_base', 10, 2)->default(0)->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn('costo_km');
        });
        Schema::table('tipo_ambulancia', function (Blueprint $table) {
            $table->dropColumn('costo_base');
        });
    }
};

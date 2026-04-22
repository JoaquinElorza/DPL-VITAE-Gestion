<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->decimal('lat_origen', 10, 7)->nullable()->after('origen');
            $table->decimal('lng_origen', 10, 7)->nullable()->after('lat_origen');
            $table->decimal('lat_destino', 10, 7)->nullable()->after('destino');
            $table->decimal('lng_destino', 10, 7)->nullable()->after('lat_destino');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['lat_origen', 'lng_origen', 'lat_destino', 'lng_destino']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->decimal('anticipo', 10, 2)->nullable()->after('costo');
            $table->string('mp_preference_id')->nullable()->after('anticipo');
            $table->string('mp_payment_id')->nullable()->after('mp_preference_id');
            $table->string('mp_pago_estado')->nullable()->after('mp_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['anticipo', 'mp_preference_id', 'mp_payment_id', 'mp_pago_estado']);
        });
    }
};

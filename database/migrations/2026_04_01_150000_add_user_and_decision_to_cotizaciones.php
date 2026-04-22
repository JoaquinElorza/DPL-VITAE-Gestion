<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id_cotizacion');
            $table->foreign('user_id')->references('id_usuario')->on('users')->nullOnDelete();
            $table->string('decision_cliente', 20)->nullable()->after('respuesta'); // confirmada | declinada
            $table->text('comentario_cliente')->nullable()->after('decision_cliente');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'decision_cliente', 'comentario_cliente']);
        });
    }
};

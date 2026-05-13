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
        Schema::table('servicio', function (Blueprint $table) {

            // operador
            $table->foreignId('id_operador')
                ->nullable()
                ->after('id_cliente')
                ->constrained('operador', 'id_usuario');

            // timestamps
            $table->timestamps();

            // soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicio', function (Blueprint $table) {

            $table->dropForeign(['id_operador']);

            $table->dropColumn([
                'id_operador',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        });
    }
};
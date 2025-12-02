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
        //
        Schema::table('triagem', function (Blueprint $table) {
            // adiciona a coluna
        $table->dropColumn('paciente_id');

         $table->unsignedBigInteger('paciente_id')
                  ->after('departamento')
                  ->nullable();

            // cria a chave estrangeira
            $table->foreign('paciente_id')
                ->references('id')->on('users')
                ->onDelete('cascade'); // se o paciente for apagado, apaga triagens
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

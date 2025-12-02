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
        Schema::create('triagem', function (Blueprint $table) {
            $table->id();
            $table->string('sintomas')->nullable();
            $table->string('categoria')->nullable();
            $table->string('gravidade')->nullable();
            $table->string('departamento')->nullable();
            $table->integer('paciente_id')->nullable();
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triagem');
    }
};

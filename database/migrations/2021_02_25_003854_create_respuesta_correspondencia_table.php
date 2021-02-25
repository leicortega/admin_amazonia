<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestaCorrespondenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuesta_correspondencia', function (Blueprint $table) {
            $table->id();
            $table->string('asunto');
            $table->string('mensaje');
            $table->string('adjunto')->nullable();

            $table->foreignId('correspondencia_id')
            ->constrained('correspondencia')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuesta_correspondencia');
    }
}

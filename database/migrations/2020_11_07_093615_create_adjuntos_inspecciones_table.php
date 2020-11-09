<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntosInspeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuntos_inspecciones', function (Blueprint $table) {
            $table->id();

            $table->string('elemento');
            $table->longText('observaciones');
            $table->string('adjunto');

            $table->foreignId('inspecciones_id')
                ->constrained()
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
        Schema::dropIfExists('adjuntos_inspecciones');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos_solicitud', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->bigInteger('valor_entregado');
            $table->bigInteger('valor_soportado');
            $table->bigInteger('saldo');

            $table->foreignId('solicitud_id')
            ->constrained('solicitudes_dinero')
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
        Schema::dropIfExists('conceptos_solicitud');
    }
}

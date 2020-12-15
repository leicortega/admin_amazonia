<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->bigInteger('vehiculo_id')->nullable();
            $table->bigInteger('conductor_uno_id')->nullable();
            $table->bigInteger('conductor_dos_id')->nullable();
            $table->bigInteger('conductor_tres_id')->nullable();
            $table->bigInteger('responsable_contrato_id')->nullable();
            $table->string('tipo_contrato')->nullable();
            $table->string('objeto_contrato')->nullable();
            $table->longText('contrato_parte_uno')->nullable();
            $table->longText('contrato_parte_dos')->nullable();
            $table->bigInteger('tercero_id')->nullable();

            // Relacion con la tabla de Cotizaciones
            $table->bigInteger('cotizacion_id');

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
        Schema::dropIfExists('contratos');
    }
}

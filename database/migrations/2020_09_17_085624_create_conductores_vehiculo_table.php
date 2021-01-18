<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductoresVehiculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conductores_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicial');
            $table->date('fecha_final');

            $table->foreignId('personal_id')
                ->constrained('personal')
                ->onDelete('cascade');

            $table->foreignId('vehiculo_id')
                ->constrained('vehiculos')
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
        Schema::dropIfExists('conductores_vehiculo');
    }
}

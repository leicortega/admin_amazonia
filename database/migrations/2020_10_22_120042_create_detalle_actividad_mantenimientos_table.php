<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleActividadMantenimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_actividad_mantenimientos', function (Blueprint $table) {
            $table->id();

            $table->string('descripcion');
            $table->string('imagen_soporte');
            $table->bigInteger('actividad_mantenimientos_id')->unsigned();

            $table->foreign('actividad_mantenimientos_id', 'actividad_mantenimientos_id_foreign')->references('id')->on('actividad_mantenimientos')->onDelete('cascade');

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
        Schema::dropIfExists('detalle_actividad_mantenimientos');
    }
}

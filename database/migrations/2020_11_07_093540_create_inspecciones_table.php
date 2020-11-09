<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspecciones', function (Blueprint $table) {
            $table->id();

            $table->dateTime('fecha_inicio');
            $table->float('kilometraje_inicio');
            $table->longText('observaciones_inicio');

            $table->dateTime('fecha_final')->nullable();
            $table->float('kilometraje_final')->nullable();
            $table->longText('observaciones_final')->nullable();

            // Relaciones
            $table->foreignId('users_id')
                ->constrained()
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
        Schema::dropIfExists('inspecciones');
    }
}

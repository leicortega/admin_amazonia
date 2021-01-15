<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadosSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados_solicitud', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['Solicitado', 'Cancelado', 'Aprobado', 'Negado', 'Entregado', 'Modificar']);
            $table->longText('descripcion');

            $table->foreignId('users_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->foreignId('conceptos_id')
            ->constrained('conceptos_solicitud')
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
        Schema::dropIfExists('estados_solicitud');
    }
}

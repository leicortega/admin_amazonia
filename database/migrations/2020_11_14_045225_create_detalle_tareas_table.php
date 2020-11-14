<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_tareas', function (Blueprint $table) {
            $table->id();

            $table->dateTime('fecha');
            $table->string('estado');
            $table->longText('observaciones');
            $table->string('adjunto')->nullable();

            $table->foreignId('tareas_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('users_id')
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
        Schema::dropIfExists('detalle_tareas');
    }
}

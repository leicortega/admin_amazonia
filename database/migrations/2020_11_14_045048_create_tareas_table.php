<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();

            $table->timestamp('fecha');
            $table->string('name_tarea');
            $table->longText('tarea');
            $table->timestamp('fecha_limite');
            $table->string('estado');
            $table->string('adjunto')->nullable();

            $table->foreignId('tipos_tareas_id')
                ->constrained('tipos_tareas')
                ->onDelete('cascade');

            $table->foreignId('supervisor')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('asignado')
                ->constrained('users')
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
        Schema::dropIfExists('tareas');
    }
}

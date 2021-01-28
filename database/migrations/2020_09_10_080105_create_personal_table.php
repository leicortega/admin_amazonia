<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_identificacion', 30);
            $table->bigInteger('identificacion');
            $table->string('nombres');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->date('fecha_ingreso');
            $table->string('direccion')->nullable();
            $table->enum('sexo', ['Hombre', 'Mujer', 'Otro']);
            $table->string('rh', 5);
            $table->enum('estado', ['Activo', 'Inactivo']);
            $table->string('tipo_vinculacion');
            $table->string('telefonos')->nullable();
            $table->string('correo')->nullable();
            $table->string('firma')->nullable();

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
        Schema::dropIfExists('personal');
    }
}

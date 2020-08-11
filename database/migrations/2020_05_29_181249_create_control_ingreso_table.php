<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlIngresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_ingresos', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->bigInteger('identificacion')->unique();
            $table->bigInteger('telefono');
            $table->integer('edad');
            $table->string('email')->unique()->nullable();
            $table->enum('tipo', ['Funcionario', 'Cliente']);

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
        Schema::dropIfExists('control_ingreso');
    }
}

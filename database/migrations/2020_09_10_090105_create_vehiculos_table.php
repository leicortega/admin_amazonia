<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();

            $table->string('placa', 9);
            $table->bigInteger('licencia_transito');
            $table->integer('modelo');
            $table->integer('capacidad');
            $table->string('numero_motor');
            $table->string('chasis');
            $table->integer('numero_interno');
            $table->integer('tarjeta_operacion');
            $table->string('color');
            $table->enum('estado', ['Activo', 'Inactivo']);
            $table->date('fecha_estado')->nullable();
            $table->text('observacion_estado')->nullable();
            $table->string('empresa_convenio')->nullable();
            $table->string('tipo_vehiculo');
            $table->string('num_carpeta_fisica')->nullable();

            $table->foreignId('tipo_vehiculo_id')->nullable()
                ->constrained('tipo_vehiculo')
                ->onDelete('cascade');

            $table->foreignId('marca_id')
                ->constrained('marca')
                ->onDelete('cascade');

            $table->foreignId('tipo_vinculacion_id')
                ->constrained('tipo_vinculacion')
                ->onDelete('cascade');

            $table->foreignId('linea_id')
                ->constrained('linea')
                ->onDelete('cascade');

            $table->foreignId('tipo_carroceria_id')
                ->constrained('tipo_carroceria')
                ->onDelete('cascade');

            $table->foreignId('personal_id')
                ->constrained('personal')
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
        Schema::dropIfExists('table_vehiculos');
    }
}

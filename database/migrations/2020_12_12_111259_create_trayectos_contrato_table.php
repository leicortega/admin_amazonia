<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrayectosContratoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trayectos_contrato', function (Blueprint $table) {
            $table->id();

            $table->datetime('fecha');
            $table->string('nombre');
            $table->string('correo');
            $table->bigInteger('telefono')->nullable();
            $table->string('departamento_origen');
            $table->string('ciudad_origen');
            $table->string('departamento_destino');
            $table->string('ciudad_destino');
            $table->date('fecha_ida');
            $table->date('fecha_regreso');
            $table->string('tipo_servicio');
            $table->string('tipo_vehiculo');
            $table->enum('recorrido', ['Solo ida', 'Ida y vuelta']);
            $table->longText('descripcion')->nullable();
            $table->longText('observaciones')->nullable();
            $table->enum('combustible', ['Si', 'No'])->nullable();
            $table->enum('conductor', ['Si', 'No'])->nullable();
            $table->enum('peajes', ['Si', 'No'])->nullable();
            $table->enum('cotizacion_por', ['Dias', 'Trayecto', 'Mensual'])->nullable();
            $table->bigInteger('valor_unitario')->nullable();
            $table->integer('cantidad')->nullable();
            $table->bigInteger('total')->nullable();
            $table->longText('trayecto_dos')->nullable();
            $table->bigInteger('vehiculo_id')->nullable();
            $table->bigInteger('conductor_uno_id')->nullable();
            $table->bigInteger('conductor_dos_id')->nullable();
            $table->bigInteger('conductor_tres_id')->nullable();

            // Relaciones
            $table->foreignId('contratos_id')
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
        Schema::dropIfExists('trayectos_contrato');
    }
}

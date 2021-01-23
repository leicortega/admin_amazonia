<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTrayectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones_trayectos', function (Blueprint $table) {
            $table->id();
            $table->string('departamento_origen');
            $table->string('ciudad_origen');
            $table->string('departamento_destino');
            $table->string('ciudad_destino');
            $table->date('fecha_ida');
            $table->date('fecha_regreso');
            $table->string('tipo_servicio');
            $table->string('tipo_vehiculo');
            $table->enum('recorrido', ['Solo ida', 'Ida y vuelta']);
            $table->longText('descripcion');
            $table->longText('observaciones');
            $table->enum('combustible', ['Si', 'No']);
            $table->enum('conductor', ['Si', 'No']);
            $table->enum('peajes', ['Si', 'No']);
            $table->enum('cotizacion_por', ['Dias', 'Trayecto', 'Mensual']);
            $table->bigInteger('valor_unitario');
            $table->integer('cantidad');
            $table->bigInteger('total');
            $table->longText('trayecto_dos');
            $table->enum('aceptado', [0,1])->nullable();
            $table->bigInteger('responsable_id')->nullable();
            
            $table->foreignId('cotizacion_id')
            ->constrained('cotizaciones')
            ->onDelete('cascade');

            $table->foreignId('vehiculo_id')->nullable()
            ->constrained('vehiculos')
            ->onDelete('cascade');

            $table->foreignId('conductor_uno_id')->nullable()
            ->constrained('personal')
            ->onDelete('cascade');

            $table->foreignId('conductor_dos_id')->nullable()
            ->constrained('personal')
            ->onDelete('cascade');

            $table->foreignId('conductor_tres_id')->nullable()
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
        Schema::dropIfExists('cotizaciones_trayectos');
    }
}

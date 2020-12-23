<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMantenimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();

            // Solicitado
            $table->dateTime('fecha');
            $table->longText('descripcion_solicitud');
            $table->enum('estado', ['Solicitado', 'Aprobado', 'Cerrado'])->default('Solicitado');

            // Contabilidad
            $table->string('persona_contabilidad', 120)->nullable();
            $table->dateTime('fecha_contabilidad')->nullable();
            $table->longText('observaciones_contabilidad')->nullable();

            // Autorizacion
            $table->string('persona_autoriza', 120)->nullable();
            $table->dateTime('fecha_autorizacion')->nullable();
            $table->longText('observaciones_autorizacion')->nullable();
            $table->enum('asume', ['Empresa', 'Propietario'])->nullable();

            // Cierre
            $table->string('persona_cierre', 120)->nullable();
            $table->dateTime('fecha_cierre')->nullable();
            $table->longText('observaciones_cierre')->nullable();


            // Relaciones
            $table->foreignId('vehiculo_id')
                ->constrained('vehiculos')
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
        Schema::dropIfExists('mantenimientos');
    }
}

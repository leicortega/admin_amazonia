<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosLegalesVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_legales_vehiculos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo', 120);
            $table->string('consecutivo', 120);
            $table->date('fecha_expedicion');
            $table->date('fecha_inicio_vigencia')->nullable();
            $table->date('fecha_fin_vigencia')->nullable();
            $table->string('entidad_expide', 120);
            $table->string('estado', 120);
            $table->string('documento_file', 120)->nullable();

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
        Schema::dropIfExists('documentos_legales_vehiculos');
    }
}

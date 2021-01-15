<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesDineroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_dinero', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_solicitud', ['Viaticos', 'Mantenimientos', 'Otros']);
            $table->date('fecha_solicitud');
            $table->longText('descripcion');

            $table->foreignId('personal_crea')
            ->constrained('users')
            ->onDelete('cascade');

            $table->foreignId('beneficiario')
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
        Schema::dropIfExists('solicitudes_dinero');
    }
}

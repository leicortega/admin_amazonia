<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivosSoportadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_soportados', function (Blueprint $table) {
            $table->id();
            $table->string('archivo');
            $table->bigInteger('valor_soporte');
            
            $table->foreignId('conceptos_solicitud_id')
            ->constrained('conceptos_solicitud')
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
        Schema::dropIfExists('archivos_soportados');
    }
}

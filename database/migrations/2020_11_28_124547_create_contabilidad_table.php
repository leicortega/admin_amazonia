<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContabilidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contabilidad', function (Blueprint $table) {
            $table->id();

            $table->string('persona_creo');
            $table->date('fecha');
            $table->string('concepto');
            $table->bigInteger('valor');
            $table->string('tipo');
            $table->string('estado')->nullable();
            $table->string('anexo')->nullable();

            $table->foreignId('vehiculos_id')
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
        Schema::dropIfExists('contabilidad');
    }
}

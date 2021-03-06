<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_cargos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cargos_id')
            ->constrained('cargos')
            ->onDelete('cascade');

            $table->foreignId('documentos_cargos_id')
            ->constrained('documentos_cargos_admin')
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
        Schema::dropIfExists('documentos_cargos');
    }
}

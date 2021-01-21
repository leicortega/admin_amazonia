<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDocumentosVehiculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_documentos_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('vigencia', [1, 0]);
            $table->string('tipo_tercero');

            $table->foreignId('categoria_id')
            ->constrained('admin_documentos_vehiculo_categoria')
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
        Schema::dropIfExists('admin_documentos_vehiculo');
    }
}

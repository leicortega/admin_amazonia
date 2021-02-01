<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosDocumentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_documentacion', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('file');
            $table->date('fecha_inicio_vigencia')->nullable();
            $table->date('fecha_fin_vigencia')->nullable();

            $table->foreignId('documentacion_id')
                ->constrained('documentacion')
                ->cascadeOnDelete();

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
        Schema::dropIfExists('documentos_documentacion');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosPersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_personal', function (Blueprint $table) {
            $table->id();

            $table->string('tipo', 60);
            $table->date('fecha_expedicion');
            $table->date('fecha_inicio_vigencia')->nullable();
            $table->date('fecha_fin_vigencia')->nullable();
            $table->longText('observaciones')->nullable();
            $table->string('adjunto', 90);

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
        Schema::dropIfExists('documentos_personal');
    }
}

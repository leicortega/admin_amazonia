<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrespondenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correspondencia', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tipo_radicacion_id')
            ->constrained('tipo_radicacion_correspondencia')
            ->onDelete('cascade');

            $table->foreignId('dependencia_id')
            ->constrained('dependencia_correspondencia')
            ->onDelete('cascade');

            $table->foreignId('users_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->string('asunto');

            $table->integer('numero_folios');

            $table->foreignId('origen_id')
            ->constrained('origen_correspondencia')
            ->onDelete('cascade');

            $table->foreignId('tercero_id')
            ->constrained('terceros')
            ->onDelete('cascade');

            $table->string('adjunto')->nullable();

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
        Schema::dropIfExists('correspondencia');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleInspeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_inspecciones', function (Blueprint $table) {
            $table->id();

            $table->string('campo');
            $table->string('cantidad');
            $table->string('estado');

            $table->foreignId('admin_inspecciones_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('inspecciones_id')
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
        Schema::dropIfExists('detalle_inspecciones');
    }
}

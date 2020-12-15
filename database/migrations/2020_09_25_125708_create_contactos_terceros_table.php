<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactosTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos_terceros', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('identificacion');
            $table->string('nombre');
            $table->bigInteger('telefono');
            $table->string('direccion');

            $table->foreignId('terceros_id')
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
        Schema::dropIfExists('contactos_terceros');
    }
}

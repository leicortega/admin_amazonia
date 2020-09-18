<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsableContratoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsable_contrato', function (Blueprint $table) {
            $table->id();

            $table->string("tipo_identificacion");
            $table->bigInteger("identificacion");
            $table->string("nombre");
            $table->string("direccion");
            $table->string("correo")->nullable();
            $table->bigInteger("telefono");
            $table->bigInteger('tercero_id')->nullable();

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
        Schema::dropIfExists('responsable_contrato');
    }
}

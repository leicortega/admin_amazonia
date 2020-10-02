<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtroSiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otro_si', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->longText('descripcion');

            $table->foreignId('contratos_personal_id')
                ->constrained('contratos_personal')
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
        Schema::dropIfExists('otro_si');
    }
}

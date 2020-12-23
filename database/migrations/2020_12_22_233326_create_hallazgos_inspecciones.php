<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallazgosInspecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hallazgos_inspecciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspecciones_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('mantenimientos_id')
                ->constrained()
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
        Schema::dropIfExists('hallazgos_inspecciones');
    }
}

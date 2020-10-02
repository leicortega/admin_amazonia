<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosPersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos_personal', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('salario');
            $table->enum('estado', ['Activo', 'Terminado', 'Suspendido']);
            $table->string('cargo', 60);
            $table->string('tipo_contrato', 60);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->longText('clausulas_parte_uno');
            $table->longText('clausulas_parte_dos')->nullable();

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
        Schema::dropIfExists('contratos_personal');
    }
}

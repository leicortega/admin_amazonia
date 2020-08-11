<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->float('temperatura');

            $table->enum('pregunta_one', ['Si', 'No']);
            $table->enum('pregunta_two', ['Si', 'No']);
            $table->enum('pregunta_three', ['Si', 'No']);
            $table->enum('fiebre', ['Si', 'No']);
            $table->enum('tos', ['Si', 'No']);
            $table->enum('gripa', ['Si', 'No']);
            $table->enum('malestar', ['Si', 'No']);
            $table->enum('dolor_cabeza', ['Si', 'No']);
            $table->enum('fatiga', ['Si', 'No']);
            $table->enum('secrecion_nasal', ['Si', 'No']);
            $table->enum('dificultad_respirar', ['Si', 'No']);
            $table->enum('dolor_garganta', ['Si', 'No']);
            $table->enum('olfato_gusto', ['Si', 'No']);
            $table->enum('diabetes', ['Si', 'No']);
            $table->enum('hipertension', ['Si', 'No']);
            $table->enum('mayor_edad', ['Si', 'No']);
            $table->enum('cancer', ['Si', 'No']);
            $table->enum('inmunodeficiencia', ['Si', 'No']);

            $table->foreignId('control_ingreso_id')
                ->constrained()
                ->onDelete('cascade');
                
            $table->string('sede', 90);

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
        Schema::dropIfExists('ingresos');
    }
}

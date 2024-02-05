<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtexpedienteelementosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtexpedienteelementos', function (Blueprint $table) {
            $table->id();
            $table->text('nombreElemento');
            $table->text('urlElemento');
            $table->foreignId('proceso_id')->constrained('mtprocesosturismomashigienicoseguro')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('mtexpedienteelementos');
    }
}

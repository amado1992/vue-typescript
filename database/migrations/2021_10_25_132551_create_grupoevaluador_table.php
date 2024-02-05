<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoevaluadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupoevaluador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_id')->constrained('mtprocesosturismomashigienicoseguro')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mtfichaexperto_id')->constrained('mtfichaexpertos')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('esJefe');
            $table->boolean('evalUno');
            $table->boolean('evalDos');
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
        Schema::dropIfExists('grupoevaluador');
    }
}

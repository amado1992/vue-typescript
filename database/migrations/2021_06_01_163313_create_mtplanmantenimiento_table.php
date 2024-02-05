<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtplanmantenimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtplanmantenimiento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gestionmtto_id')->unsigned();
            $table->string('descMtto');
            $table->string('unidadMedida');
            $table->decimal('accPrevTPlan')->nullable();
            $table->decimal('accPrevTReal')->nullable();
            $table->decimal('accPrevTPorCiento')->nullable();
            $table->decimal('accPrevCPlan')->nullable();
            $table->decimal('accPrevCReal')->nullable();
            $table->decimal('accPrevCPorCiento')->nullable();
            $table->decimal('impTotal')->nullable();
            $table->decimal('impContratado')->nullable();
            $table->decimal('totalAccMtto')->nullable();
            $table->decimal('porCientoImpAcc')->nullable();
            $table->decimal('hDD')->nullable();
            $table->decimal('hFO')->nullable();
            $table->decimal('porCientoHFOHDD')->nullable();
            $table->timestamps();
//            $table->foreign('gestionmtto_id')->on('mtgestionmantenimiento')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtplanmantenimiento') ;
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTImportacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtimportacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mes');
            $table->integer('anno');
            $table->integer('plan');
            $table->integer('real');
            $table->integer('empresa_id')->unsigned();
            $table->integer('indicador_id')->unsigned();
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
        Schema::dropIfExists('mtimportacions');
    }
}

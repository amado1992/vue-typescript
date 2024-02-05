<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrazaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_traza', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->integer('accion_id')->unsigned();
            $table->integer('usuario_id')->unsigned()->nullable();
            $table->integer('user_sa_id')->unsigned()->nullable();
            $table->string('modelo')->nullable();
            $table->longText('descripcion');
            $table->timestamps();
            $table->foreign('accion_id')->on('event_naccion')->references('id');
            $table->foreign('usuario_id')->on('users')->references('id');
            $table->foreign('user_sa_id')->on('user_sa')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_traza');
    }
}

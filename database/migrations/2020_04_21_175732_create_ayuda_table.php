<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAyudaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_ayuda', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('topico');
            $table->longText('subtopico');
            $table->string('consecutivo');
            $table->longText('contenido');
            $table->longText('ruta');
            $table->integer('usuario_id')->unsigned();
            $table->integer('modulo')->unsigned()->default(1);
            $table->boolean('reset')->default(false);
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
        Schema::dropIfExists('event_ayuda');
    }
}

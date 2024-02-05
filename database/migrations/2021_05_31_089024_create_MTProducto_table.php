<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtproducto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->require();
            $table->string('descripcion')->require();
            $table->string('um')->require();
            $table->integer('estado');


            $table->integer('familia_prod_id')->unsigned();
            $table->foreign('familia_prod_id')->on('mtfamiliaproducto')->references('id');

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
        Schema::dropIfExists('mtproducto');
    }
}

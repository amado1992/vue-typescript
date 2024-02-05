<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTColecticoLiderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtcolectivolider', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sector_id')->unsigned();
            $table->integer('provincia_id')->unsigned();
            $table->integer('osde_id')->unsigned();
            $table->integer('instalacion_id')->unsigned();
            $table->longText('argumentacion');
            $table->string('estado')->nullable();
            $table->timestamps();
            $table->foreign('sector_id')->on('mtsector')->references('id');
            $table->foreign('provincia_id')->on('mtprovincia')->references('id');
            $table->foreign('osde_id')->on('mtosde')->references('id');
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtcolectivolider');
    }
}

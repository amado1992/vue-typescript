<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTPlanRecape extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtplan_recape', function (Blueprint $table) {
            $table->id();
            $table->integer('instalacion_id')->unsigned()->nullable();
            $table->foreign('instalacion_id')
                ->references('id')
                ->on('mtinstalacion');
            $table->date('fecha')->required();
            $table->integer('potencial')->required();
            $table->integer('anno')->required();
            $table->integer('plan_recape')->required();
            $table->string('observaciones')->nullable();
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
        Schema::dropIfExists('mtplan_recape');
    }
}

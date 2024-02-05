<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtequiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtequipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');

            $table->unsignedBigInteger('sistema_id');
            $table->foreign('sistema_id')->references('id')->on('mtsistemas')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('mtequipos');
    }
}

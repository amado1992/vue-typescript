<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMttipoinstturismomashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mttipoinstturismomashs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->integer('mttipoinst_id')->unsigned();
            $table->foreign('mttipoinst_id')->on('mttipoinst')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('mtvalor_id')
                ->nullable()
                ->constrained('mtvalores')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('mttipoinstturismomashs');
    }
}

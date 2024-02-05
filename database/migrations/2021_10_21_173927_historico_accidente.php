<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HistoricoAccidente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_accidente', function (Blueprint $table) {
            $table->id();
            $table->integer('accidente_id')->unsigned()->nullable();
            $table->foreign('accidente_id')
                ->references('id')
                ->on('mtgestionaraccidentes')
                ->onDelete('cascade');
            $table->string('estado')->required();
            $table->date('fecha')->required();
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
        //
    }
}

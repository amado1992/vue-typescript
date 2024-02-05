<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTAgenteCausalCasosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtagente_causal_casos', function (Blueprint $table) {
      $table->id();
      $table->string('codigo');
      $table->string('agente_causal_caso');
      $table->bigInteger('tipocaso_id')->unsigned();
      $table->foreign('tipocaso_id')->on('mttipo_casos')->references('id');
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
    Schema::dropIfExists('mtagente_causal_casos');
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTAgenteCausalBrotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtagente_causal_brotes', function (Blueprint $table) {
      $table->id();
      $table->string('codigo');
      $table->string('agente_causal_brote');
      $table->bigInteger('tipobrote_id')->unsigned();
      $table->foreign('tipobrote_id')->on('mttipo_brotes')->references('id');
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
    Schema::dropIfExists('mtagente_causal_brotes');
  }
}

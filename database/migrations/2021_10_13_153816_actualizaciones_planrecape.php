<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActualizacionesPlanrecape extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtactualizaciones_plan_recape', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_id')->unsigned()->nullable();
            $table->foreign('plan_id')
                ->references('id')
                ->on('mtplan_recape')
                ->onDelete('cascade');;
            $table->date('fecha_cumplimiento')->required();
            $table->integer('recapados')->required();
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
        Schema::dropIfExists('mtactualizaciones_plan_recape');
    }
}

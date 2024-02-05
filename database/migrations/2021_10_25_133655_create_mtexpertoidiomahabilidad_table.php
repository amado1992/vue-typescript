<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtexpertoidiomahabilidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtexpertoidiomahabilidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mtexpertoidioma_id')->constrained('expertoidioma')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mthabilidad_id')->constrained('mthabilidades')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mtevaluacion_id')->constrained('mtevaluaciones')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('mtexpertoidiomahabilidad');
    }
}

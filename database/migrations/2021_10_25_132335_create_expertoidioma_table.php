<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertoidiomaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expertoidioma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mtidioma_id')->constrained('mtidiomas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mtfichaexperto_id')->constrained('mtfichaexpertos')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('expertoidioma');
    }
}

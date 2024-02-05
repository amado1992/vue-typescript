<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MtdocumentoResumenEhe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtdocumento_resumen_ehe', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->text('url');
            $table->foreignId('ehe_id')
                ->constrained('mtevento_higienico_epidemiologico')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('mtdocumento_resumen_ehe');
    }
}

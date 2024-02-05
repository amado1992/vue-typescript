<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtfichaexpertosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtfichaexpertos', function (Blueprint $table) {
            $table->id();
            //Breve perfil del experto
            $table->string('perfil', 150);//Required
            //Informacion personal
            $table->string('nombreApellidos');//Required
            $table->string('carnetIdentidad');//Required
            $table->string('direccion');//Required
            //Required
            $table->integer('mtmunicipio_id')->unsigned();
            $table->foreign('mtmunicipio_id')->on('mtmunicipio')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->string('phone');//Required
            $table->string('email');//Required
            //Informacion laboral
            $table->string('instalacionLaboral');//Required
            $table->string('departamentoLaboral');//Required
            //$table->foreignId('mtmunicipioLaboral_id')->constrained('mtmunicipio')->onDelete('cascade')->onUpdate('cascade');
            $table->string('phoneLaboral')->nullable();
            $table->string('emailLaboral');//Required
            $table->string('paginaWebLaboral')->nullable();
            $table->integer('annosExperienciaSectorLaboral');//Required
            $table->string('cargoLaboral');//Required

            $table->foreignId('entidad_id')
                ->nullable()
                ->constrained('mtentidadestmhs')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('mtueb_id')
                ->nullable()
                ->constrained('mtuebs')->onDelete('cascade')->onUpdate('cascade');
            //Required
            $table->foreignId('mtoace_id')->constrained('mtoaces')->onDelete('cascade')->onUpdate('cascade');
            //Educacion
            $table->string('tituloAcademico');//Required
            $table->string('nombreInstitucionAcademica');//Required
            $table->date('fechaAcademica');//Required
            //Required
            $table->foreignId('mtcategdocentecientifica_id')->constrained('mtcategoriasdocentecientifica')->onDelete('cascade')->onUpdate('cascade');
            //Idioma
            //Anexar curriculo
            $table->string('curriculum');//Required;
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
        Schema::dropIfExists('mtfichaexpertos');
    }
}

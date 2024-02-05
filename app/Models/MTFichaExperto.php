<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MTFichaExperto extends Model
{
    protected $table = "mtfichaexpertos"; //nombre de la tabla
    protected $fillable =
        ['perfil',
        'nombreApellidos',
        'carnetIdentidad',
        'direccion',
        'mtmunicipio_id',
        'phone',
        'email',

        'instalacionLaboral',
        'departamentoLaboral',
        'phoneLaboral',
        'emailLaboral',
        'paginaWebLaboral',
        'annosExperienciaSectorLaboral',
        'cargoLaboral',
        'mtoace_id',
        'mtueb_id',
        'entidad_id',

        'tituloAcademico',
        'nombreInstitucionAcademica',
        'fechaAcademica',
        'mtcategdocentecientifica_id'];//campos que son llenados en el formulario

    public function oace()
    {
        return $this->belongsTo(MTOace::class, 'mtoace_id');
    }
    public function municipio()
    {
        return $this->belongsTo('App\Models\MTMunicipio', 'mtmunicipio_id');
    }

    public function categoria_docente_cientifica()
    {
        return $this->belongsTo('App\Models\MTCategoriaDocenteCientifica', 'mtcategdocentecientifica_id');
    }

    public function idiomas(){

        return $this->belongsToMany(MTIdioma::class,'expertoidioma');
    }

    public function entidad()
    {
        return $this->belongsTo('App\Models\MTEntidadTMHS', 'entidad_id');
    }
}

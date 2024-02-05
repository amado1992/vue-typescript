<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEncargado extends Model
{
    public $table = 'mtencargado';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'capacitacion',
        'foto',
        'almacen_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'apellidos' => 'string',
        'email' => 'string',
        'telefono' => 'string',
        'capacitacion' => 'string',
        'foto' => 'string',
        'almacen_id' => 'integer',
    ];

    public static $rules = [
        'nombre' => 'required',
        'apellidos' => 'required',
        'email' => 'required',
        'telefono' => 'required',
        'capacitacion' => 'required',
        'foto' => 'required',
        'almacen_id' => 'required',
    ];


    public function almacenes()
    {
        return $this->belongsTo(\App\Models\MTAlmacen::class, 'almacen_id');
    }

}

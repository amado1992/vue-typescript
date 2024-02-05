<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTMediosAlmacenamiento extends Model
{
    public $table = 'mtmediosalmacenamiento';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',
        'bueno',
        'regular',
        'malo',
        'almacen_id'

    ];

    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'bueno' => 'integer',
        'regular' => 'integer',
        'malo' => 'integer',
        'almacen_id' => 'integer',
    ];

    public static $rules = [
        'nombre' => 'required',
        'bueno' => 'required',
        'regular' => 'required',
        'malo' => 'required',
        'almacen_id' => 'required',
    ];


    public function almacenes()
    {
        return $this->belongsTo(\App\Models\MTAlmacen::class, 'almacen_id');
    }

}

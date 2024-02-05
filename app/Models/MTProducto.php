<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTProducto extends Model
{
    public $table = 'mtproducto';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'codigo',
        'descripcion',
        'um',
        'estado',
        'familia_prod_id'

    ];

    protected $casts = [
        'codigo' => 'string',
        'descripcion' => 'string',
        'um' => 'string',
        'estado' => 'integer',
        'familia_prod_id'=> 'integer'
    ];

    public static $rules = [
        'codigo' => 'required',
        'descripcion' => 'required',
        'um'=> 'required',
        'estado'=> 'required',
        'familia_prod_id'=> 'required'
    ];


    public function familiaProductos()
    {
        return $this->belongsTo(\App\Models\MTFamiliaProducto::class, 'familia_prod_id');
    }

    public function compras()
    {
        return $this->hasMany(\App\Models\MTCompra::class);
    }
}

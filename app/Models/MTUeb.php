<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTUeb extends Model
{
  public $table = 'mtuebs';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'ueb', 'empresa_id', 'direccion', 'correo', 'telefono'
  ];

  protected $casts = [
    'activo' => 'string',
    'ueb' => 'string',
    'empresa_id' => 'integer',
    'direccion' => 'string',
    'correo' => 'string',
    'telefono' => 'integer'
  ];

  public static $rules = [
    'activo' => 'required',
    'ueb' => 'required',
    'empresa_id' => 'required',
    'direccion' => 'required',
    'correo' => 'required',
    'telefono' => 'required'
  ];

  public function empresas()
  {
    return $this->belongsTo(MTEmpresa::class, 'empresa_id');
  }

//  protected $table = "mtuebs"; //nombre de la tabla
//  protected $fillable = ['nombre', 'descripcion, mtempresa_id'];//campos que son llenados en el formulario
//
//  public function empresa()
//  {
//    return $this->belongsTo(MTEmpresa::class, 'mtempresa_id');
//  }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEmpresa extends Model
{
  public $table = 'mtempresas';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'empresa', 'tipo_empresa', 'osde_id',  'direccion', 'correo', 'telefono'
  ];

  protected $casts = [
    'activo' => 'string',
    'empresa' => 'string',
    'tipo_empresa' => 'string',
    'osde_id' => 'integer',
    'direccion' => 'string',
    'correo' => 'string',
    'telefono' => 'integer'
  ];

  public static $rules = [
    'activo' => 'required',
    'empresa' => 'required',
    'tipo_empresa' => 'required',
    'osde_id' => 'required',
    'direccion' => 'required',
    'correo' => 'required',
    'telefono' => 'required'
  ];

  public function osdes()
  {
    return $this->belongsTo(MTOsde::class, 'osde_id');
  }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTSucursal extends Model
{
  public $table = 'mtsucursals';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'sucursal', 'complejo', 'empresa_id', 'direccion', 'correo', 'telefono'
  ];

  protected $casts = [
    'activo' => 'string',
    'sucursal' => 'string',
    'complejo' => 'string',
    'empresa_id' => 'integer',
    'direccion' => 'string',
    'correo' => 'string',
    'telefono' => 'integer'
  ];

  public static $rules = [
    'activo' => 'required',
    'sucursal' => 'required',
    'empresa_id' => 'required',
    'direccion' => 'required',
    'correo' => 'required',
    'telefono' => 'required'
  ];

  public function empresas()
  {
    return $this->belongsTo(MTEmpresa::class, 'empresa_id');
  }
}

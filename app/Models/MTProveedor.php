<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTProveedor extends Model
{
  public $table = 'mtproveedor';
  public $model = 'MTProveedor';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo',
    'nombre',
    'municipio_id',
    'tipo_proveedor_id'
  ];

  protected $casts = [
    'id' => 'integer',
    'codigo' => 'string',
    'nombre' => 'string',
    'municipio_id' => 'integer',
    'tipo_proveedor_id' => 'integer'
  ];

  public static $rules = [
    'codigo' => 'required',
    'nombre' => 'required|alpha',
    'municipio_id' => 'required',
    'tipo_proveedor_id' => 'required'
  ];

  public function proveedores()
  {
    return $this->belongsTo(\App\Models\MTTipoProveedor::class, 'tipo_proveedor_id');
  }

  public function municipios()
  {
    return $this->belongsTo(\App\Models\MTMunicipio::class, 'municipio_id');
  }

  public function compra()
  {
    return $this->hasMany(\App\Models\MTCompra::class, 'proveedor_id');
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCompra extends Model
{
  public $table = 'mtcompra';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'fecha_cierre',
    'cantidad_real',
    'cantidad_plan',
    'precio',
    'inventario',
    'total_compras',
    'compras_nacionales',
    'instalacion_id',
    'producto_id',
    'proveedor_id',
    'unidadmedida_id'
  ];

  protected $casts = [
    'id' => 'integer',
    'fecha_cierre' => 'date',
    'cantidad_real' => 'integer',
    'cantidad_plan' => 'integer',
    'precio' => 'float',
    'inventario' => 'double',
    'total_compras' => 'float',
    'compras_nacionales' => 'float',
    'instalacion_id' => 'integer',
    'producto_id' => 'integer',
    'proveedor_id' => 'integer',
    'unidadmedida_id' => 'integer'
  ];

  public static $rules = [
    'fecha_cierre' => 'required',
    'cantidad_real' => 'required',
    'cantidad_plan' => 'required',
    'precio' => 'required',
    'inventario' => 'required',
    'total_compras' => 'required',
    'compras_nacionales' => 'required',
    'producto_id' => 'required',
    'proveedor_id' => 'required',
    'unidadmedida_id' => 'required'
  ];


  public function instalaciones()
  {
    return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
  }

  public function productos()
  {
    return $this->belongsTo(Producto::class, 'producto_id');
  }

  public function proveedores()
  {
    return $this->belongsTo(MTProveedor::class, 'proveedor_id');
  }

  public function unidades()
  {
    return $this->belongsTo(MTUnidadMedida::class, 'unidadmedida_id');
  }

}

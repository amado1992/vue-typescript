<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTAlmacen extends Model
{
  public $table = 'mtalmacen';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'nombre',
    'codigo',
    'direccion',
    'latitud',
    'longitud',
    'largo',
    'ancho',
    'puntal_libre',
    'altura_prom_estiba',
    'area_util',
    'volumen_util',
    'cant_montacargas',
    'cant_transpaletas',
    'cant_esteras',
    'cant_basculas',
    'cant_estantes',
    'cant_paletas',
    'cant_cajas_paletas',
    'estado_constructivo',
    'estado_estructura',
    'estado_paredes',
    'estado_piso',
    'estado_puertas',
    'estado_ventanas',
    'estado_techo',
    'ventilacion_natural',
    'ventilacion_artificial',
    'iluminacion_natural',
    'iluminacion_artificial',
    'prot_contra_intrusos',
    'prot_contra_incendios',
    'prot_observaciones',
    'plan_inversiones',
    'instalacion_id',
    'categoria_id',
    'distribucion_id',
    'tipo_almacen_id',
    'actividad_id',
    'temperatura_id'
  ];

  protected $casts = [
    'id' => 'integer',
    'nombre'=> 'string',
    'codigo'=> 'string',
    'direccion'=> 'string',
    'latitud' => 'float',
    'longitud' => 'float',
    'largo' => 'float',
    'ancho' => 'float',
    'puntal_libre' => 'float',
    'altura_prom_estiba' => 'float',
    'area_util' => 'float',
    'volumen_util' => 'float',
    'cant_montacargas'=> 'string',
    'cant_transpaletas'=> 'string',
    'cant_esteras'=> 'string',
    'cant_basculas'=> 'string',
    'cant_estantes'=> 'string',
    'cant_paletas'=> 'string',
    'cant_cajas_paletas'=> 'string',
    'estado_constructivo' => 'string',
    'estado_estructura' => 'string',
    'estado_paredes' => 'string',
    'estado_piso' => 'string',
    'estado_puertas' => 'string',
    'estado_ventanas' => 'string',
    'estado_techo' => 'string',
    'ventilacion_natural' => 'string',
    'ventilacion_artificial' => 'string',
    'iluminacion_natural' => 'string',
    'iluminacion_artificial' => 'string',
    'prot_contra_intrusos' => 'string',
    'prot_contra_incendios' => 'string',
    'prot_observaciones' => 'string',
    'plan_inversiones' => 'string',
    'instalacion_id' => 'integer',
    'categoria_id' => 'integer',
    'distribucion_id' => 'integer',
    'tipo_almacen_id' => 'integer',
    'actividad_id' => 'integer',
    'temperatura_id' => 'integer'
  ];

  public static $rules = [
    'nombre'=> 'required',
    'codigo'=> 'required',
    'direccion'=> 'required',
    'latitud' => 'required',
    'longitud' => 'required',
    'largo' => 'required',
    'ancho' => 'required',
    'puntal_libre' => 'required',
    'altura_prom_estiba' => 'required',
    'area_util' => 'required',
    'volumen_util' => 'required',
    'cant_montacargas'=> 'required',
    'cant_transpaletas'=> 'required',
    'cant_esteras'=> 'required',
    'cant_basculas'=> 'required',
    'cant_estantes'=> 'required',
    'cant_paletas'=> 'required',
    'cant_cajas_paletas'=> 'required',
    'estado_constructivo' => 'required',
    'estado_estructura' => 'required',
    'estado_paredes' => 'required',
    'estado_piso' => 'required',
    'estado_puertas' => 'required',
    'estado_ventanas' => 'required',
    'estado_techo' => 'required',
    'ventilacion_natural' => 'required',
    'ventilacion_artificial' => 'required',
    'iluminacion_natural' => 'required',
    'iluminacion_artificial' => 'required',
    'prot_contra_intrusos' => 'required',
    'prot_contra_incendios' => 'required',
    'prot_observaciones' => 'required',
    'plan_inversiones' => 'required',
    'categoria_id' => 'required',
    'distribucion_id' => 'required',
    'tipo_almacen_id' => 'required',
    'actividad_id' => 'required',
    'temperatura_id' => 'required'
  ];

  public function instalaciones()
  {
    return $this->belongsTo(\App\Models\MTInstalacion::class, 'instalacion_id');
  }

  public function categorias()
  {
    return $this->belongsTo(\App\Models\MTCategoria::class, 'categoria_id');
  }

  public function distribuciones()
  {
    return $this->belongsTo(\App\Models\MTDistribucion::class, 'distribucion_id');
  }

  public function tipoAlmacenes()
  {
    return $this->belongsTo(\App\Models\MTTipoAlmacen::class, 'tipo_almacen_id');
  }

  public function actividades()
  {
    return $this->belongsTo(\App\Models\MTActividad::class, 'actividad_id');
  }

  public function temperaturas()
  {
    return $this->belongsTo(\App\Models\MTTemperatura::class, 'temperatura_id');
  }

  public function encargados()
  {
    return $this->hasMany(\App\Models\MTEncargado::class);
  }

}

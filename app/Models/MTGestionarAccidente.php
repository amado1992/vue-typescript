<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTGestionarAccidente extends Model
{
  public $table = 'mtgestionaraccidentes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'instalacion_id', 'radicacion', 'fecha', 'lugar', 'hora', 'imputable', 'accidente_via', 'accidente_base',
    'clasificacion_accidente_id', 'victima_accidentes_id', 'fallecido', 'herido',
    'perdidas_materiales', 'vehiculo_empresa_id', 'vehiculo_ajeno_id', 'causa_accidente_id',
    'annos_experiencia', 'nombre_apellidos', 'expediente_laboral', 'estacion_pnr',
    'tribunal_competente', 'atestado'
  ];

  protected $casts = [
    'id' => 'integer',
    'instalacion_id' => 'integer',
    'radicacion' => 'string',
    'fecha' => 'date',
    'lugar' => 'string',
    'hora' => 'time',
    'imputable' => 'boolean',
    'accidente_via' => 'boolean',
    'accidente_base' => 'boolean',
    'clasificacion_accidente_id' => 'integer',
    'victima_accidentes_id' => 'integer',
    'fallecido' => 'integer',
    'herido' => 'integer',
    'perdidas_materiales' => 'float',
    'vehiculo_empresa_id' => 'integer',
    'causa_accidente_id' => 'integer',
    'annos_experiencia' => 'integer',
    'nombre_apellidos' => 'string',
    'expediente_laboral' => 'string',
    'estacion_pnr' => 'string',
    'tribunal_competente' => 'string',
    'atestado' => 'integer'
  ];

  public static $rules = [
    'radicacion' => 'required',
    'fecha' => 'required',
    'lugar' => 'required',
    'hora' => 'required',
    'imputable' => 'required',
    'accidente_via' => 'required',
    'accidente_base' => 'required',
    'clasificacion_accidente_id' => 'required',
    'victima_accidentes_id' => 'required',
    'fallecido' => 'integer',
    'herido' => 'integer',
    'perdidas_materiales' => 'required',
    'vehiculo_empresa_id' => 'integer',
    'causa_accidente_id' => 'required',
    'annos_experiencia' => 'required',
    'nombre_apellidos' => 'required',
    'expediente_laboral' => 'required',
    'estacion_pnr' => 'string',
    'tribunal_competente' => 'string',
    'atestado' => 'integer'
  ];

  public function instalaciones(){
    return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
  }

  public function clasificacionaccidentes(){
    return $this->belongsTo(MTClasificacionAccidente::class,'clasificacion_accidente_id');
  }
  public function victimaaccidentes(){
    return $this->belongsTo(MTVictimaAccidente::class,'victima_accidentes_id');
  }
  public function vehiculoempresas(){
    return $this->belongsTo(MTMedioTransporte::class,'vehiculo_empresa_id');
  }
  public function vehiculoajenos(){
    return $this->belongsTo(MTVehiculoAjeno::class,'vehiculo_ajeno_id');
  }
  public function causaaccidentes(){
    return $this->belongsTo(MTCausaAccidente::class,'causa_accidente_id');
  }

}

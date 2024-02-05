<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTOficinaEmpleo extends Model
{
  public $table = 'mtoficina_empleos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'oficina_empleo', 'direccion', 'delegacion_territorial_id', 'telefono'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'oficina_empleo' => 'string',
    'direccion' => 'string',
    'delegacion_territorial_id' => 'integer',
    'telefono' => 'integer'
  ];

  public static $rules = [
    'activo' => 'required',
    'oficina_empleo' => 'required',
    'direccion' => 'required',
    'delegacion_territorial_id' => 'required',
    'telefono' => 'required'
  ];

  public function delegacionesterritoriales(){
    return $this->belongsTo(MTDelegacionTerritorial::class,'delegacion_territorial_id');
  }
}

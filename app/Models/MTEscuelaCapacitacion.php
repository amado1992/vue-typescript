<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEscuelaCapacitacion extends Model
{
  public $table = 'mtescuela_capacitacions';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'escuela_capacitacion', 'direccion', 'delegacion_territorial_id', 'telefono'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'escuela_capacitacion' => 'string',
    'direccion' => 'string',
    'delegacion_territorial_id' => 'integer',
    'telefono' => 'integer'
  ];

  public static $rules = [
    'activo' => 'required',
    'escuela_capacitacion' => 'required',
    'direccion' => 'required',
    'delegacion_territorial_id' => 'required',
    'telefono' => 'required'
  ];

  public function delegacionesterritoriales(){
    return $this->belongsTo(MTDelegacionTerritorial::class,'delegacion_territorial_id');
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTInfotur extends Model
{
  public $table = 'mtinfoturs';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'infotur', 'direccion', 'delegacion_territorial_id', 'telefono'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'infotur' => 'string',
    'direccion' => 'string',
    'delegacion_territorial_id' => 'integer',
    'telefono' => 'integer'
  ];

  public static $rules = [
    'activo' => 'required',
    'infotur' => 'required',
    'direccion' => 'required',
    'delegacion_territorial_id' => 'required',
    'telefono' => 'required'
  ];

  public function delegacionesterritoriales(){
    return $this->belongsTo(MTDelegacionTerritorial::class,'delegacion_territorial_id');
  }
}

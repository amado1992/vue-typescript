<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTDelegacionTerritorial extends Model
{
  public $table = 'mtdelegacion_territorials';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo', 'delegacion_territorial', 'territorio_id'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'delegacion_territorial' => 'string',
    'territorio_id' => 'integer',
  ];

  public static $rules = [
    'activo' => 'required',
    'delegacion_territorial' => 'required',
    'territorio_id' => 'required'
  ];

  public function provincias(){
    return $this->belongsTo(MTProvincia::class,'territorio_id');
  }
}

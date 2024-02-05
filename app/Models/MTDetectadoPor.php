<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTDetectadoPor extends Model
{
  public $table = 'mtdetectado_por';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'detectado_por'
  ];

  protected $casts = [
    'codigo' => 'string',
    'detectado_por' => 'string'
  ];

  public static $rules = [
    'detectado_por' => 'required'
  ];

}

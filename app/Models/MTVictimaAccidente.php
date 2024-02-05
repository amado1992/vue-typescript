<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTVictimaAccidente extends Model
{
  public $table = 'mtvictimaaccidentes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'victima_accidente',
  ];

  protected $casts = [
    'id' => 'integer',
    'victima_accidente' => 'string',
  ];

  public static $rules = [
    'victima_accidente' => 'required',
  ];
}

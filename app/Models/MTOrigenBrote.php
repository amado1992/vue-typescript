<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTOrigenBrote extends Model
{
  public $table = 'mtorigen_brotes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'origen_brote'
  ];

  protected $casts = [
    'codigo' => 'string',
    'origen_brote' => 'string'
  ];

  public static $rules = [
    'origen_brote' => 'required'
  ];

}

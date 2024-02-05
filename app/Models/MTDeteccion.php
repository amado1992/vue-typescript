<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTDeteccion extends Model
{
  public $table = 'mtdeteccion';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'deteccion'
  ];

  protected $casts = [
    'codigo' => 'string',
    'deteccion' => 'string'
  ];

  public static $rules = [
    'codigo' => 'required',
    'deteccion' => 'required'
  ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTOace extends Model
{
  public $table = 'mtoaces';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo',
    'siglas',
    'oace'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'siglas' => 'string',
    'oace' => 'string'
  ];

  public static $rules = [
    'activo' => 'required',
    'siglas' => 'required',
    'oace' => 'required'
  ];

}

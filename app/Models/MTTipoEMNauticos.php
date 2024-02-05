<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoEMNauticos extends Model
{
  public $table = 'mttipoemnauticos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'tipo',
  ];

  protected $casts = [
    'id' => 'integer',
    'tipo' => 'string',
  ];

  public static $rules = [
    'tipo' => 'required',
  ];
}

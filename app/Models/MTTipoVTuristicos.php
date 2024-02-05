<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoVTuristicos extends Model
{
  public $table = 'mttipovturisticos';

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

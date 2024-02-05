<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoCMTransporte extends Model
{
  public $table = 'mttipocmtransportes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'combustible',
  ];

  protected $casts = [
    'id' => 'integer',
    'combustible' => 'string',
  ];

  public static $rules = [
    'combustible' => 'required',
  ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTMotivoPMTransporte extends Model
{
  public $table = 'mtmotivopmtransportes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'motivo',
  ];

  protected $casts = [
    'id' => 'integer',
    'motivo' => 'string',
  ];

  public static $rules = [
    'motivo' => 'required',
  ];
}

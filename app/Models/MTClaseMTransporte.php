<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTClaseMTransporte extends Model
{
  public $table = 'mtclasemtransportes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'clase',
  ];

  protected $casts = [
    'id' => 'integer',
    'clase' => 'string',
  ];

  public static $rules = [
    'clase' => 'required',
  ];
}

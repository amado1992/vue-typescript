<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCausaAccidente extends Model
{
  public $table = 'mtcausaaccidentes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'causa_accidente',
  ];

  protected $casts = [
    'id' => 'integer',
    'causa_accidente' => 'string',
  ];

  public static $rules = [
    'causa_accidente' => 'required',
  ];
}

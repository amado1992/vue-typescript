<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTPatogenoIdentificado extends Model
{
  public $table = 'mtpatogeno_identificado';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'patogeno_identificado'
  ];

  protected $casts = [
    'codigo' => 'string',
    'patogeno_identificado' => 'string'
  ];

  public static $rules = [
    'codigo' => 'required',
    'patogeno_identificado' => 'required'
  ];

}

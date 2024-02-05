<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTOrigenCaso extends Model
{
  public $table = 'mtorigen_casos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'origen_caso'
  ];

  protected $casts = [
    'codigo' => 'string',
    'origen_caso' => 'string'
  ];

  public static $rules = [
    'origen_caso' => 'required'
  ];

}

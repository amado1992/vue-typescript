<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCategoriaInstalacion extends Model
{
  public $table = 'mtcategoria_instalacions';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo',
    'categoria_instalacion'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'categoria_instalacion' => 'string'
  ];

  public static $rules = [
    'activo' => 'required',
    'categoria_instalacion' => 'required'
  ];
}

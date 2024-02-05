<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCadenaHotelera extends Model
{
  public $table = 'mtcadena_hoteleras';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo',
    'cadena_hotelera',
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'cadena_hotelera' => 'string'
  ];

  public static $rules = [
    'activo' => 'required',
    'cadena_hotelera' => 'required'
  ];
}

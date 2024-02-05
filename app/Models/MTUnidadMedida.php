<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTUnidadMedida extends Model
{
  public $table = 'mtunidadmedida';
  public $model = 'MTUnidadMedida';


  const CREATED_AT = 'created_at';

  public $fillable = [
    'nombre',
  ];

  protected $casts = [
    'id' => 'integer',
    'nombre' => 'string',
  ];

  public static $rules = [
    'nombre' => 'required',
  ];

  public function compra()
  {
    return $this->hasMany(\App\Models\MTCompra::class, 'unidadmedida_id');
  }
}

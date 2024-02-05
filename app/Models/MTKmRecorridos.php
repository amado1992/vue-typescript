<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTKmRecorridos extends Model
{
  public $table = 'mtkm_recorridos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'mes', 'anno', 'km_recorridos', 'vehiculo_empresa_id'
  ];

  protected $casts = [
    'mes' => 'integer',
    'anno' => 'integer',
    'km_recorridos' => 'float',
    'vehiculo_empresa_id' => 'integer'
  ];

  public static $rules = [
    'mes' => 'required',
    'anno' => 'required',
    'km_recorridos' => 'required',
    'vehiculo_empresa_id' => 'required'
  ];

  public function vehiculos()
  {
    return $this->belongsTo(MTMedioTransporte::class, 'vehiculo_empresa_id');
  }
}

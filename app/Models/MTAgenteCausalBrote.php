<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTAgenteCausalBrote extends Model
{
  public $table = 'mtagente_causal_brotes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'agente_causal_brote', 'tipobrote_id'
  ];

  protected $casts = [
    'codigo' => 'string',
    'agente_causal_brote' => 'string',
    'tipobrote_id' => 'integer'
  ];

  public static $rules = [
    'agente_causal_brote' => 'required',
    'tipobrote_id' => 'required'
  ];

  public function brotes()
  {
    return $this->belongsTo(\App\Models\MTTipoBrote::class, 'tipobrote_id');
  }
}

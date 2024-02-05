<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTAgenteCausalCaso extends Model
{
  public $table = 'mtagente_causal_casos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'agente_causal_caso', 'tipocaso_id'
  ];

  protected $casts = [
    'codigo' => 'string',
    'agente_causal_caso' => 'string',
    'tipocaso_id' => 'integer'
  ];

  public static $rules = [
    'agente_causal_caso' => 'required',
    'tipocaso_id' => 'required'
  ];

  public function casos()
  {
    return $this->belongsTo(\App\Models\MTTipoCaso::class, 'tipocaso_id');
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MTSistGestionDemandaServicio
 * @package App\Models
 * @version Jule 23, 2021, 4:27 pm CDT
 *
 * @property string $cod_DemandaServicio
 * @property string $desc_DemandaServicio

 */
class MTSistGestionDemandaServicio extends Model
{
  public $table = 'mtsistgestiondemandaservicios';
  public $model = 'MTSistGestionDemandaServicio';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'cod_DemandaServicio',
    'desc_DemandaServicio',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'cod_DemandaServicio' => 'string',
    'desc_DemandaServicio' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
  ];
}

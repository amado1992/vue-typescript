<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MTSistGestionTiposSistGestion
 * @package App\Models
 * @version Jule 23, 2021, 4:27 pm CDT
 *
 * @property string $desc_TipoSistGestion
 * @property string $norma_Referencia

 */
class MTSistGestionTiposSistGestion extends Model
{
  public $table = 'mtsistgestiontipossistgestion';
  public $model = 'MTSistGestionTiposSistGestion';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'desc_TipoSistGestion',
    'norma_Referencia',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'desc_TipoSistGestion' => 'string',
    'norma_Referencia' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
  ];
}

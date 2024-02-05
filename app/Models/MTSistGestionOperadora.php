<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MTSistGestionOperadora
 * @package App\Models
 * @version Jule 23, 2021, 4:27 pm CDT
 *
 * @property string $desc_Operadora

 */
class MTSistGestionOperadora extends Model
{
  public $table = 'mtsistgestionoperadoras';
  public $model = 'MTSistGestionOperadora';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'desc_Operadora',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'desc_Operadora' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
  ];
}

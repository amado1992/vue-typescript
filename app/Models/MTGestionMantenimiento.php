<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MTGestionMantenimiento
 * @package App\Models
 * @version Agost 25, 2021, 11:05 am CDT
 *
 * @property string $descripcion
 * @property integer $instalacion_id
 * @property integer $anno
 * @property integer $mes
 */

class MTGestionMantenimiento extends Model
{
  public $table = 'mtgestionmantenimiento';
  public $model = 'MTGestionMantenimiento';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'descripcion',
    'instalacion_id',
    'anno',
    'mes',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'descripcion' => 'string',
    'instalacion_id' => 'integer',
    'anno' => 'integer',
    'mes' => 'integer',
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [

  ];

  // Relacion uno a muchos
  public function planmantenimientos()
  {
    return $this->hasMany(MTPlanMantenimiento::class, 'gestionmtto_id','id');
  }

  // Relacion uno a uno
  public function instalacions()
  {
    return $this->hasMany(MTInstalacion::class, 'id','instalacion_id');
  }
}

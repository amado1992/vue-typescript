<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTOsde
 * @package App\Models
 * @version June 7, 2021, 2:32 pm CDT
 *
 * @property \Illuminate\Database\Eloquent\Collection $mtentidads
 * @property \App\Models\MTProvincia $provincia
 * @property \App\Models\MTOace $oaces
 * @property string $activo
 * @property string $nombre
 
 * @property string $tipo
 * @property integer $provincia_id
 * @property integer $oace_id
 */
class MTOsde extends Model
{

  public $table = 'mtosde';
  public $model = 'Osde';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'activo',
    'nombre',
    'tipo',
    'provincia_id',
    'oace_id'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'nombre' => 'string',
    'tipo' => 'string',
    'provincia_id' => 'integer',
    'oace_id' => 'integer'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
    'activo' => 'required',
    'nombre' => 'required',
    'tipo' => 'required',
    'provincia_id' => 'required',
    'oace_id' => 'required'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function entidades()
  {
    return $this->hasMany(Mtentidad::class, 'osde_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/
  public function provincia()
  {
    return $this->belongsTo(MTProvincia::class, 'provincia_id');
  }

  public function oace()
  {
    return $this->belongsTo(MTOace::class, 'oace_id');
  }

    public function instalaciones()
    {
      return $this->hasMany(Mtinstalacion::class, 'osde_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTEntidad
 * @package App\Models
 * @version June 7, 2021, 4:08 pm CDT
 *
 * @property \App\Models\MTOsde $osde
 * @property \Illuminate\Database\Eloquent\Collection $mtdisponibilidadhabitaciones
 * @property \Illuminate\Database\Eloquent\Collection $mtinstalacions
 * @property string $nombre
 * @property string $codigo
 * @property integer $osde_id
 */
class MTEntidad extends Model
{

    public $table = 'mtentidad';
    public $model = 'Entidad';


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'nombre',
        'codigo',
        'osde_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'codigo' => 'string',
        'osde_id' => 'integer'
    ];
  protected $with = [
    //'mtinstalacions'
  ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function osde()
    {
        return $this->belongsTo(MTOsde::class, 'osde_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mtdisponibilidadhabitaciones()
    {
        return $this->hasMany(MTDisponibilidadHabitaciones::class, 'entidad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
//    public function mtinstalacions()
//    {
//        return $this->hasMany(Mtinstalacion::class, 'entidad_id');
//    }
}

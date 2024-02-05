<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTColectivoLider
 * @package App\Models
 * @version June 9, 2021, 1:40 pm CDT
 *
 * @property \App\Models\Mtmunicipio $instalacion
 * @property \App\Models\Mtinstalacion $osde
 * @property \App\Models\MTProvincia $provincia
 * @property \App\Models\Mtsector $sector
 * @property integer $sector_id
 * @property integer $provincia_id
 * @property integer $osde_id
 * @property integer $instalacion_id
 * @property string $argumentacion
 * @property boolean $estado
 */
class MTColectivoLider extends Model
{

    public $table = 'mtcolectivolider';
    public $model = 'ColectivoLider';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'sector_id',
        'provincia_id',
        'osde_id',
        'instalacion_id',
        'argumentacion',
        'estado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'sector_id' => 'integer',
        'provincia_id' => 'integer',
        'osde_id' => 'integer',
        'instalacion_id' => 'integer',
        'argumentacion' => 'string',
        'estado' => 'string'
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
    public function instalacion()
    {
        return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function osde()
    {
        return $this->belongsTo(MTOsde::class, 'osde_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function provincia()
    {
        return $this->belongsTo(MTProvincia::class, 'provincia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function sector()
    {
        return $this->belongsTo(MTSector::class, 'sector_id');
    }
}

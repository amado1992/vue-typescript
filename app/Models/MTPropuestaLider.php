<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTPropuestaLider
 * @package App\Models
 * @version June 10, 2021, 9:35 am CDT
 *
 * @property \App\Models\Mtinstalacion $instalacion
 * @property \App\Models\Mtosde $osde
 * @property \App\Models\MTProvincia $provincia
 * @property \App\Models\Mtsector $sector
 * @property string $nombre
 * @property string $apellido
 * @property string $cargo
 * @property integer $sector_id
 * @property integer $provincia_id
 * @property integer $osde_id
 * @property integer $instalacion_id
 * @property string $argumentacion
 * @property string $estado
 */
class MTPropuestaLider extends Model
{

    public $table = 'mtpropuestalider';
    public $model = 'PropuestaLider';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'nombre',
        'apellido',
        'cargo',
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
        'nombre' => 'string',
        'apellido' => 'string',
        'cargo' => 'string',
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

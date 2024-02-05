<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTDemanda
 * @package App\Models
 * @version June 23, 2021, 9:56 am CDT
 *
 * @property \App\Models\Familia $familia
 * @property \App\Models\Mtinstalacion $instalacion
 * @property \App\Models\MTMes $mes
 * @property \App\Models\Producto $producto
 * @property integer $instalacion_id
 * @property integer $familia_id
 * @property integer $producto_id
 * @property string $unidad_medida
 * @property integer $enero
 * @property integer $febrero
 * @property integer $marzo
 * @property integer $abril
 * @property integer $mayo
 * @property integer $junio
 * @property integer $julio
 * @property integer $agosto
 * @property integer $septiembre
 * @property integer $octubre
 * @property integer $noviembre
 * @property integer $diciembre
 * @property integer $anno
 * @property integer $total_demanda_anual
 * @property integer $aprobado
 * @property integer $deficit
 * @property number $porciento_aprobado
 */
class MTDemanda extends Model
{

    public $table = 'mtdemanda';
    public $model = 'Demanda';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'instalacion_id',
        'familia_id',
        'producto_id',
        'unidad_medida',
        'enero',
        'febrero',
        'marzo',
        'abril',
        'mayo',
        'junio',
        'julio',
        'agosto',
        '$eptiembre',
        'octubre',
        'noviembre',
        'diciembre',
        'anno',
        'total_demanda_anual',
        'aprobado',
        'deficit',
        'porciento_aprobado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'instalacion_id' => 'integer',
        'familia_id' => 'integer',
        'producto_id' => 'integer',
        'unidad_medida' => 'string',
        'enero' => 'integer',
        'febrero' => 'integer',
        'marzo' => 'integer',
        'abril' => 'integer',
        'mayo' => 'integer',
        'junio' => 'integer',
        'julio' => 'integer',
        'agosto' => 'integer',
        '$eptiembre' => 'integer',
        'octubre' => 'integer',
        'noviembre' => 'integer',
        'diciembre' => 'integer',
        'anno' => 'integer',
        'total_demanda_anual' => 'integer',
        'aprobado' => 'integer',
        'deficit' => 'integer',
        'porciento_aprobado' => 'float'
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
    public function familia()
    {
        return $this->belongsTo(Familia::class, 'familia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function instalacion()
    {
        return $this->belongsTo(Mtinstalacion::class, 'instalacion_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}

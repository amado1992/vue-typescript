<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTPresMantenimiento
 * @package App\Models
 * @version June 17, 2021, 7:12 pm CDT
 *
 * @property integer $gestionmtto_id
 * @property string $descMtto
 * @property string $unidadMedida
 * @property number $prespMttoT1
 * @property number $prespMttoT2
 * @property number $prespMttoTotal
 * @property number $prespMttoC1
 * @property number $prespMttoC2
 * @property number $prespMttoContrat
 * @property number $realMttoT1
 * @property number $realMttoT2
 * @property number $realMttoTotal
 * @property number $realMttoC1
 * @property number $realMttoC2
 * @property number $realMttoContrat
 * @property number $porCientoMttoTot1
 * @property number $porCientoMttoTot2
 * @property number $porCientoMttoTot3
 * @property number $porCientoMttoContrat1
 * @property number $porCientoMttoContrat2
 * @property number $porCientoMttoContrat3
 */
class MTPresMantenimiento extends Model
{

    public $table = 'mtpresmantenimiento';
    public $model = 'MTPresMantenimiento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'gestionmtto_id',
        'descMtto',
        'unidadMedida',
        'prespMttoT1',
        'prespMttoT2',
        'prespMttoTotal',
        'prespMttoC1',
        'prespMttoC2',
        'prespMttoContrat',
        'realMttoT1',
        'realMttoT2',
        'realMttoTotal',
        'realMttoC1',
        'realMttoC2',
        'realMttoContrat',
        'porCientoMttoTot1',
        'porCientoMttoTot2',
        'porCientoMttoTot3',
        'porCientoMttoContrat1',
        'porCientoMttoContrat2',
        'porCientoMttoContrat3'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'gestionmtto_id' => 'integer',
        'descMtto' => 'string',
        'unidadMedida' => 'string',
        'prespMttoT1' => 'decimal:2',
        'prespMttoT2' => 'decimal:2',
        'prespMttoTotal' => 'decimal:2',
        'prespMttoC1' => 'decimal:2',
        'prespMttoC2' => 'decimal:2',
        'prespMttoContrat' => 'decimal:2',
        'realMttoT1' => 'decimal:2',
        'realMttoT2' => 'decimal:2',
        'realMttoTotal' => 'decimal:2',
        'realMttoC1' => 'decimal:2',
        'realMttoC2' => 'decimal:2',
        'realMttoContrat' => 'decimal:2',
        'porCientoMttoTot1' => 'decimal:2',
        'porCientoMttoTot2' => 'decimal:2',
        'porCientoMttoTot3' => 'decimal:2',
        'porCientoMttoContrat1' => 'decimal:2',
        'porCientoMttoContrat2' => 'decimal:2',
        'porCientoMttoContrat3' => 'decimal:2',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}

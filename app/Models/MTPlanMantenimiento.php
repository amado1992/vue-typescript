<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MTPlanMantenimiento
 * @package App\Models
 * @version June 1, 2021, 4:27 pm CDT
 *
 * @property integer $gestionmtto_id
 * @property string $descMtto
 * @property string $unidadMedida
 * @property number $accPrevTPlan
 * @property number $accPrevTReal
 * @property number $accPrevTPorCiento
 * @property number $accPrevCPlan
 * @property number $accPrevCReal
 * @property number $accPrevCPorCiento
 * @property number $impTotal
 * @property number $impContratado
 * @property number $totalAccMtto
 * @property number $porCientoImpAcc
 * @property number $hDD
 * @property number $hFO
 * @property number $porCientoHFOHDD
 */
class MTPlanMantenimiento extends Model
{

    public $table = 'mtplanmantenimiento';
    public $model = 'MTPlanMantenimiento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'gestionmtto_id',
        'descMtto',
        'unidadMedida',
        'accPrevTPlan',
        'accPrevTReal',
        'accPrevTPorCiento',
        'accPrevCPlan',
        'accPrevCReal',
        'accPrevCPorCiento',
        'impTotal',
        'impContratado',
        'totalAccMtto',
        'porCientoImpAcc',
        'hDD',
        'hFO',
        'porCientoHFOHDD',
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
        'accPrevTPlan' => 'decimal:0',
        'accPrevTReal' => 'decimal:0',
        'accPrevTPorCiento' => 'decimal:0',
        'accPrevCPlan' => 'decimal:0',
        'accPrevCReal' => 'decimal:0',
        'accPrevCPorCiento' => 'decimal:0',
        'impTotal' => 'decimal:0',
        'impContratado' => 'decimal:0',
        'totalAccMtto' => 'decimal:0',
        'porCientoImpAcc' => 'decimal:0',
        'hDD' => 'decimal:0',
        'hFO' => 'decimal:0',
        'porCientoHFOHDD' => 'decimal:0',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];



//    public function entidads()
//    {
//        return $this->hasMany(MTEntidad::class, 'id','entidad');
//    }


        /*public function createAnexoDos(Request $request)
        {
            $datosTabla = $request['datas'];
            $datosAdicionales = $request['attributos'];
            $entidad = $request['entidad'];
    //        foreach ($datosTabla as $aux)
    //        {
    //            print_r($aux);
    //            $this->descMtto = $aux['name'];
    //            $this->unidadMedida = $aux['um'];
    //            $this->accPrevTPlan = $aux['aptp'];
    //            $this->accPrevTReal = $aux['aptr'];
    //            $this->accPrevTPorCiento = $aux['aptpp'];
    //            $this->accPrevCPlan = $aux['apcp'];
    //            $this->accPrevCReal = $aux['apcr'];
    //            $this->accPrevCPorCiento = $aux['apcc'];
    //            $this->impTotal = $aux['it'];
    //            $this->impContratado = $aux['ic'];
    //            $this->totalAccMtto = $aux['tam'];
    //            $this->porCientoImpAcc = $aux['pit'];
    //            $this->hDD = $datosAdicionales['hdd'];
    //            $this->hFO = $datosAdicionales['hfo'];
    //            $this->porCientoHFOHDD = $datosAdicionales['porcientoH'];
    //            $this->mes = $datosAdicionales['mes'];
    //            $this->anno = $datosAdicionales['anno'];
    //            $this->entidad = $entidad;
    //            $this->save();
    //        }
            for ($i = 0; $i < count($datosTabla); $i++) {
                if($i <  5)
                {
                    try {
                        $this->descMtto = $datosTabla[$i]['name'];
                        $this->unidadMedida = $datosTabla[$i]['um'];
                        $this->accPrevTPlan = $datosTabla[$i]['aptp'];
                        $this->accPrevTReal = $datosTabla[$i]['aptr'];
                        $this->accPrevTPorCiento = $datosTabla[$i]['aptpp'];
                        $this->accPrevCPlan = $datosTabla[$i]['apcp'];
                        $this->accPrevCReal = $datosTabla[$i]['apcr'];
                        $this->accPrevCPorCiento = $datosTabla[$i]['apcc'];
                        $this->impTotal = $datosTabla[$i]['it'];
                        $this->impContratado = $datosTabla[$i]['ic'];
                        $this->totalAccMtto = $datosTabla[$i]['tam'];
                        $this->porCientoImpAcc = $datosTabla[$i]['pit'];
                        $this->hDD = $datosAdicionales['hdd'];
                        $this->hFO = $datosAdicionales['hfo'];
                        $this->porCientoHFOHDD = $datosAdicionales['porcientoH'];
                        $this->mes = $datosAdicionales['mes'];
                        $this->anno = $datosAdicionales['anno'];
                        $this->entidad = $entidad;
                    } catch (\Exception $exception) {
                        Log::error($exception->getMessage());
                    }
                    $this->save();
                }

            }

        }*/
}

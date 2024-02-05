<?php

namespace App\Repositories;

use App\Models\MTPlanMantenimiento;
use App\Repositories\BaseRepository;

/**
 * Class PlanMantenimientoRepository
 * @package App\Repositories
 * @version June 1, 2021, 4:27 pm CDT
*/

class PlanMantenimientoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MTPlanMantenimiento::class;
    }
}

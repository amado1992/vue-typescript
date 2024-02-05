<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\MTCostoCalidadNoConformidad;

/**
 * Class MTCostoCalidadNoConformidadRepository
 * @package App\Repositories
 * @version Agosto 12, 2021, 11:00 am CDT
 */
class MTCostoCalidadNoConformidadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'noconformidad_id',
        'reportCostoCalidad_id',
        'importe',
        'acumulado',
        'tipo'
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
        return MTCostoCalidadNoConformidad::class;
    }

    public function listCostoCalidadNoConformidad()
    {
        $costocalidad = $this->model->with([
            'costo_no_conformidad:id,nombre',
            'costo_calidad_report:id,nombre'
        ])->orderBy('created_at', 'desc')->get();

        if (!isset($costocalidad) || $costocalidad == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
        return response()->json($costocalidad);
    }
}

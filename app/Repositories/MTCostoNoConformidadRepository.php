<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\MTCostoNoConformidad;
use App\Utils\EventsUtil;

/**
 * Class MTCostoNoConformidadRepository
 * @package App\Repositories
 * @version Agosto 16, 2021, 11:00 am CDT
 */
class MTCostoNoConformidadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'nombre',
        'tipo',
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
        return MTCostoNoConformidad::class;
    }

    public function listCostoNoConformidad($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('tipo', 'like', '%' . $filter . '%')
            ->orderByDesc('tipo')
            ->paginate($request['itemsPerPage']);
    }
}

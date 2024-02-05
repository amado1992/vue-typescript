<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\MTCostoConformidad;
use App\Utils\EventsUtil;

/**
 * Class MTCostoConformidadRepository
 * @package App\Repositories
 * @version Agosto 16, 2021, 10:08 am CDT
 */
class MTCostoConformidadRepository extends BaseRepository
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
        return MTCostoConformidad::class;
    }

    public function listCostoConformidad($request)
    {
       /* $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('tipo', 'like', '%' . $filter . '%')
            ->orderByDesc('tipo')
            ->paginate($request['itemsPerPage']);*/

        $filter = $request['search'];
        return $this->model->paginate($request['itemsPerPage']);
    }
}

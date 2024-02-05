<?php

namespace App\Repositories;

use App\Models\Ayuda;
use App\Repositories\BaseRepository;

/**
 * Class AyudaRepository
 * @package App\Repositories
 * @version March 10, 2020, 7:28 pm UTC
*/

class AyudaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'topico',
        'subtopico',
        'consecutivo',
        'ruta',
        'usuario_id',
        'modulo'
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
        return Ayuda::class;
    }

    public function getAllRuta($payload)
    {
        return $this->model->where('ruta', $payload)->orderByDesc('created_at')->get();
    }
}

<?php

namespace App\Repositories;

use App\Models\Familia;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class FamiliaRepository
 * @package App\Repositories
 * @version May 27, 2021, 2:42 pm CDT
*/

class FamiliaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre_familia',
        'descripcion'
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
        return Familia::class;
    }

    public function getFamilias()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

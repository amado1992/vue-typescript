<?php

namespace App\Repositories;

use App\Models\MTProvincia;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTProvinciaRepository
 * @package App\Repositories
 * @version June 8, 2021, 8:12 am CDT
*/

class MTProvinciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre'
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
        return MTProvincia::class;
    }

    public function getProvincias()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

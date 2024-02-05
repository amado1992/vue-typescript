<?php

namespace App\Repositories;

use App\Models\MTAvalApci;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTAvalApciRepository
 * @package App\Repositories
*/

class MTAvalApciRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'estado'
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
        return MTAvalApci::class;
    }

    public function getAvales()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

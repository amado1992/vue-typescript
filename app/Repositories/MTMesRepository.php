<?php

namespace App\Repositories;

use App\Models\MTMes;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTMesRepository
 * @package App\Repositories
 * @version June 23, 2021, 9:57 am CDT
*/

class MTMesRepository extends BaseRepository
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
        return MTMes::class;
    }
    public function getMeses()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

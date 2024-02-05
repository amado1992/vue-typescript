<?php

namespace App\Repositories;

use App\Models\MTSector;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTSectorRepository
 * @package App\Repositories
 * @version June 8, 2021, 3:45 pm CDT
*/

class MTSectorRepository extends BaseRepository
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
        return MTSector::class;
    }
    public function getSectores()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

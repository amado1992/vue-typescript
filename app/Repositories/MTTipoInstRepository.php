<?php

namespace App\Repositories;

use App\Models\MTTipoInst;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTTipoInstRepository
 * @package App\Repositories
 * @version June 8, 2021, 9:41 am CDT
*/

class MTTipoInstRepository extends BaseRepository
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
        return MTTipoInst::class;
    }
    public function getTiposInst()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

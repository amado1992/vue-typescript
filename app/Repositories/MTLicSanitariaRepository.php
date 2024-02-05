<?php

namespace App\Repositories;

use App\Models\MTLicSanitaria;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTLicSanitariaRepository
 * @package App\Repositories
*/

class MTLicSanitariaRepository extends BaseRepository
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
        return MTLicSanitaria::class;
    }

    public function getLicencias()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

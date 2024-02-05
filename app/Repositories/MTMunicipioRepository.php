<?php

namespace App\Repositories;

use App\Models\MTMunicipio;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTMunicipioRepository
 * @package App\Repositories
 * @version June 8, 2021, 8:50 am CDT
*/

class MTMunicipioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'provincia_id'
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
        return MTMunicipio::class;
    }

    public function getAllPaginateMunicipio($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'provincia:id,nombre'
        ])
            ->where('nombre', 'like', '%' . $filter . '%')
            ->orWhereHas('provincia', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
    public function getMunicipios()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

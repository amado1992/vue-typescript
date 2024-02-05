<?php

namespace App\Repositories;

use App\Models\MTEntidad;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTEntidadRepository
 * @package App\Repositories
 * @version June 7, 2021, 4:08 pm CDT
 */
class MTEntidadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'codigo',
        'osde_id'
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
        return MTEntidad::class;
    }

    public function getEntidades()
    {
        return DB::table($this->model->getTable())->orderBy('nombre')->get();
    }

    public function getAllPaginateEntidad($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'osde:id,nombre'
        ])
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('codigo', 'like', '%' . $filter . '%')
            ->orWhereHas('osde', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

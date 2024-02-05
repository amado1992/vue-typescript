<?php

namespace App\Repositories;

use App\Models\MTOsde;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTOsdeRepository
 * @package App\Repositories
 * @version June 7, 2021, 2:32 pm CDT
*/

class MTOsdeRepository extends BaseRepository
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
        return MTOsde::class;
    }

    public function getAllPaginateOsde($request)
    {
      $filter = $request['search'];
      //$callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
      return $this->model->with([
        'provincia:id,nombre'
      ])
        ->with([
          'oace:id,siglas'
        ])
        ->where('nombre', 'like', '%' . $filter . '%')
        //->orWhereHas('provincia', $callBack)
        ->orderByDesc('created_at')
        ->paginate($request['itemsPerPage']);
    }

    public function getOsdes()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
}

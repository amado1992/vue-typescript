<?php


namespace App\Repositories;

use App\Models\MTOace;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTOaceRepository extends BaseRepository
{

    protected $fieldSearchable = [
      'activo', 'siglas', 'oace'
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

    public function model()
    {
        return MTOace::class;
    }

    public function listMTOace()
    {
        $mtOace = DB::table($this->model->getTable())->orderByDesc('created_at')
          ->where('activo',1)->get();
        if (!isset($mtOace) || $mtOace == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay oace que mostrar']);
        return $mtOace;
    }

    public function getAllPaginateOace($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('activo', 'like', '%' . $filter . '%')
            ->where('siglas', 'like', '%' . $filter . '%')
            ->where('oace', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

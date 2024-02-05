<?php


namespace App\Repositories;

use App\Models\MTMecanismoTransmision;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTMecanismoTransmisionRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'mecanismo_transmision'
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
        return MTMecanismoTransmision::class;
    }

    public function listMTMecanismoTransmision()
    {
        $mtMecanismoTransmision = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtMecanismoTransmision) || $mtMecanismoTransmision == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
        return $mtMecanismoTransmision;
    }

    public function getAllPaginateMecanismoTransmision($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('mecanismo_transmision', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

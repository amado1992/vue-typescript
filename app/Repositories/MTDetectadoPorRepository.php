<?php


namespace App\Repositories;

use App\Models\MTDetectadoPor;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTDetectadoPorRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'detectado_por'
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
        return MTDetectadoPor::class;
    }

    public function listMTDetectadoPor()
    {
        $mtDetectadoPor = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtDetectadoPor) || $mtDetectadoPor == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay detectado por que mostrar']);
        return $mtDetectadoPor;
    }

    public function getAllPaginateDetectadoPor($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('detectado_por', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

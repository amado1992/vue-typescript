<?php


namespace App\Repositories;

use App\Models\MTVehiculoTransmision;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTVehiculoTransmisionRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'vehiculo'
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
        return MTVehiculoTransmision::class;
    }

    public function listMTVehiculo()
    {
        $mtVehiculo = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtVehiculo) || $mtVehiculo == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
        return $mtVehiculo;
    }

    public function getAllPaginateVehiculo($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('vehiculo', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

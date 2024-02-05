<?php


namespace App\Repositories;

use App\Models\MTCadenaHotelera;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTCadenaHoteleraRepository extends BaseRepository
{

    protected $fieldSearchable = [
      'activo', 'cadena_hotelera'
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
        return MTCadenaHotelera::class;
    }

    public function listMTCadenaHotelera()
    {
        $data = DB::table($this->model->getTable())->orderByDesc('created_at')
          ->where('activo',1)->get();
        if (!isset($data) || $data == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay CadenaHotelera que mostrar']);
        return $data;
    }

    public function getAllPaginateCadenaHotelera($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('activo', 'like', '%' . $filter . '%')
            ->where('cadena_hotelera', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

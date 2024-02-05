<?php


namespace App\Repositories;

use App\Models\MTEscuelaRamal;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTEscuelaRamalRepository extends BaseRepository
{

    protected $fieldSearchable = [
      'activo', 'codigo', 'nombre'
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
        return MTEscuelaRamal::class;
    }

    public function listMTEscuelaRamal()
    {
        $data = DB::table($this->model->getTable())->orderByDesc('created_at')
          ->where('activo',1)->get();
        if (!isset($data) || $data == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resultado que mostrar']);
        return $data;
    }

    public function getAllPaginateEscuelaRamal($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('activo', 'like', '%' . $filter . '%')
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('nombre', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

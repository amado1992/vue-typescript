<?php


namespace App\Repositories;

use App\Models\MTUnidadAdministrativa;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTUnidadAdministrativaRepository extends BaseRepository
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
        return MTUnidadAdministrativa::class;
    }

    public function listMTUnidadAdministrativa()
    {
        $data = DB::table($this->model->getTable())->orderByDesc('created_at')
          ->where('activo',1)->get();
        if (!isset($data) || $data == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resultado que mostrar']);
        return $data;
    }

    public function getAllPaginateUnidadAdministrativa($request)
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

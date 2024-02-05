<?php


namespace App\Repositories;

use App\Models\MTCategoriaInstalacion;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTCategoriaInstalacionRepository extends BaseRepository
{

    protected $fieldSearchable = [
      'activo', 'categoria_instalacion'
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
        return MTCategoriaInstalacion::class;
    }

    public function listMTCategoriaInstalacion()
    {
        $mtCategoriaInstalacion = DB::table($this->model->getTable())->orderByDesc('created_at')
          ->where('activo',1)->get();
        if (!isset($mtCategoriaInstalacion) || $mtCategoriaInstalacion == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay CategoriaInstalacion que mostrar']);
        return $mtCategoriaInstalacion;
    }

    public function getAllPaginateCategoriaInstalacion($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('activo', 'like', '%' . $filter . '%')
            ->where('categoria_instalacion', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

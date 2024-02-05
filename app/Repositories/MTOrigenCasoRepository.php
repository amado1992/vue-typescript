<?php


namespace App\Repositories;

use App\Models\MTOrigenCaso;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTOrigenCasoRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'origen_caso'
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
        return MTOrigenCaso::class;
    }

    public function listMTOrigenCaso()
    {
        $mtOrigenCaso = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtOrigenCaso) || $mtOrigenCaso == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay origen de caso que mostrar']);
        return $mtOrigenCaso;
    }

    public function getAllPaginateOrigenCaso($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('origen_caso', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

<?php


namespace App\Repositories;

use App\Models\MTTipoCaso;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTTipoCasoRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'tipo_caso'
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
        return MTTipoCaso::class;
    }

    public function listMTTipoCaso()
    {
        $mtTipoCaso = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtTipoCaso) || $mtTipoCaso == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipo de caso que mostrar']);
        return $mtTipoCaso;
    }

    public function getAllPaginateTipoCaso($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('tipo_caso', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

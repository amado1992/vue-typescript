<?php


namespace App\Repositories;

use App\Models\MTTipoBrote;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTTipoBroteRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'tipo_brote'
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
        return MTTipoBrote::class;
    }

    public function listMTTipoBrote()
    {
        $mtTipoBrote = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtTipoBrote) || $mtTipoBrote == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
        return $mtTipoBrote;
    }

    public function getAllPaginateTipoBrote($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('tipo_brote', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

<?php


namespace App\Repositories;

use App\Models\MTOrigenBrote;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTOrigenBroteRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'codigo',
        'origen_brote'
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
        return MTOrigenBrote::class;
    }

    public function listMTOrigenBrote()
    {
        $mtOrigenBrote = DB::table($this->model->getTable())->orderByDesc('created_at')->get();
        if (!isset($mtOrigenBrote) || $mtOrigenBrote == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
        return $mtOrigenBrote;
    }

    public function getAllPaginateOrigenBrote($request)
    {
        $filter = $request['search'];
        return $this->model
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('origen_brote', 'like', '%' . $filter . '%')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}

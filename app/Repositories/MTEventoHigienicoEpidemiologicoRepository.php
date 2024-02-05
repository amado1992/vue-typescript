<?php

namespace App\Repositories;

use App\Models\MTEventoHigienicoEpidemiologico;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

class MTEventoHigienicoEpidemiologicoRepository extends BaseRepository
{

    protected $fieldSearchable = [];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return MTEventoHigienicoEpidemiologico::class;
    }

    public function paginateListEventoEH($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'instalaciones:id,nombre,provincia_id,osde_id',
            'instalaciones.provincia:id,nombre',
            'instalaciones.osdes:id,nombre',
        ])
            ->WhereHas('instalaciones', $callBack)
            ->paginate($request['itemsPerPage']);
    }

    public function genareteCodRegistro()
    {
        $year = now()->isoFormat('YY');
        $obj = $this->model->orderBy('id', 'desc')->first();
        return (str_pad((($obj === null) ? 1 : $obj->id + 1), 2, '0', STR_PAD_LEFT)) . '-' . $year;
    }
}

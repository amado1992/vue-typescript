<?php


namespace App\Repositories;

use App\Models\MTIndicador;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;


class MTIndicadorRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'nombre',
        'renglon_id',
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
        return MTIndicador::class;
    }

    public function listMTIndicador()
    {
        $mtindicador  = $this->model->orderBy('created_at','desc')->get();

        if(!isset($mtindicador) || $mtindicador == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay Importación que mostrar']);
        return $mtindicador;
    }

    public function getAllPaginateIndicador($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('renglon_id', 'like', '%' . $filter . '%');
        return $this->model->with([
            'renglon'
        ])
            ->where('nombre', 'like', '%' . $filter . '%')
            ->orWhereHas('renglon', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }

}


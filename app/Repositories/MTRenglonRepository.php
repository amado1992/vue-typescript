<?php


namespace App\Repositories;

use App\Models\MTRenglon;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;


class MTRenglonRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'nombre',
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
        return MTRenglon::class;
    }

    public function listMTRenglon()
    {
        $mtRenglon  = $this->model->orderBy('created_at','desc')->get();

        if(!isset($mtRenglon) || $mtRenglon==null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay Data que mostrar']);
        return $mtRenglon;
    }


}


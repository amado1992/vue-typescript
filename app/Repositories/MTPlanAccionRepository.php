<?php

namespace App\Repositories;

use App\Models\MTPlanAccionFicheros;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;

class MTPlanAccionRepository extends BaseRepository
{

    protected $fieldSearchable = [];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return MTPlanAccionFicheros::class;
    }

    public function listplanAccion_($request)
    {
        return $this->model
            ->where('ehe_id', $request['id'])
            ->get();
    }
}

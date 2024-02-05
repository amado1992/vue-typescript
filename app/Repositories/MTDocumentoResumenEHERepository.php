<?php

namespace App\Repositories;

use App\Models\MTDocumentoResumenEHE;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;

class MTDocumentoResumenEHERepository extends BaseRepository
{

    protected $fieldSearchable = [];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return MTDocumentoResumenEHE::class;
    }

    public function listDocumentosResumen_($request)
    {
        return $this->model
            ->where('ehe_id', $request['id'])
            ->get();
    }
}

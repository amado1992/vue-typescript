<?php

namespace App\Repositories;

use App\Models\MTHistoricoVehiculo;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class MTMedioTransporteRepository
 * @package App\Repositories
 */
class MTHistoricoVehiculoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'vehiculo_id',
        'estado',
        'estado_fechaInicial',
        'estado_fechaFinal',
        'cant_accidentes',
        'fecha_accidente',
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

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MTHistoricoVehiculo::class;
    }

    /**
     * Return an array of MTHistoricoVehiculo with pagination.
     **/
    public function listHistoricoVehiculo_paginate()
    {
        return 'Hello repository ;)';
    }

    /**
     * Return an array of MTHistoricoVehiculo.
     **/
    public function listHistoricoVehiculo_($request)
    {
        return $this->model
            ->where('vehiculo_id', $request['id'])
            ->get();
    }

    public function getHistorico_($request)
    {
        return DB::table('mtgestionaraccidentes')
            ->select('fecha')
            ->selectRaw("count(id) as cant_accidentes")
            ->where('vehiculo_empresa_id', $request['vehiculo_id'])
            ->groupBy('fecha')
            ->get();
    }
}

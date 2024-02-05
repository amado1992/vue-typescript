<?php

namespace App\Http\Controllers\API;

use App\Models\MTDisponibilidadHabitaciones;
use App\Repositories\MTDisponibilidadHabitacionesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class MTDisponibilidadHabitacionesController extends AppBaseController
{
    private $mtDisponibilidadHabitacionesRepository;
    private $mtDisponibilidadHabitaciones;

    public function __construct(MTDisponibilidadHabitacionesRepository $mtDisponibilidadHabitacionesRepository, MTDisponibilidadHabitaciones $mtDisponibilidadHabitaciones )
    {
        $this->mtDisponibilidadHabitacionesRepository = $mtDisponibilidadHabitacionesRepository;
        $this->mtDisponibilidadHabitaciones = $mtDisponibilidadHabitaciones;
    }

    public function index(Request $request)
    {
        $data = $this->mtDisponibilidadHabitacionesRepository->listMTDisponibilidadHabitaciones($request);
        return $data;
    }

    public function mayorIncidencia()
    {
        $data = $this->mtDisponibilidadHabitacionesRepository->listMayorIncidencia();
        return $data;
    }

    public function listHabitacionesNoDisponibles(Request $request)
    {
        $data=$this->mtDisponibilidadHabitacionesRepository->listHabitacionesNoDisponibles($request);
        return $data;
    }

    public function ListPorcientoEntidades()
    {
        $data=$this->mtDisponibilidadHabitacionesRepository->ListPorcientoEntidades();
        return $data;
    }

    public function store(Request $request)
    {
        $validate = validator($request->all(), [
            'cant_habitaciones_nd' => 'required|unique:mtdisponibilidadhabitaciones',
            'entidad_id'=> 'required|unique:mtdisponibilidadhabitaciones',
            'causa_id'=> 'required|unique:mtdisponibilidadhabitaciones',
            'clasificacion_id'=> 'required|unique:mtdisponibilidadhabitaciones'
        ]);

        if ($validate->fails())
            redirect()->back()->withInput()->withErrors($validate->errors());
        /** @var User $user */
        $mtDisponibilidadHabitaciones = $this->mtDisponibilidadHabitacionesRepository->create($request->all());

        if (!$mtDisponibilidadHabitaciones) return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);

        return response()->json(['success' => true, 'data' => null, 'msg' => 'Disponibilidad de habitaciones creada']);
    }

    public function update(Request $request, $id)
    {
        $data = $this->mtDisponibilidadHabitacionesRepository->updateMTDisponibilidadHabitaciones($id,$request);
        return $data;
    }

    public function destroy(Request $request, $id)
    {
        $data = $this->mtDisponibilidadHabitacionesRepository->eliminarMTDisponibilidadHabitaciones($id,$request);
        return $data;
    }

}

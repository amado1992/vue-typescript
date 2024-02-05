<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTEquipoResource;
use App\Models\MTEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipos = MTEquipo::with('sistema')->paginate(100);

        return MTEquipoResource::collection($equipos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'sistema_id' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $equipo = new MTEquipo();
        $equipo->nombre = $request->nombre;
        $equipo->descripcion = $request->descripcion;
        $equipo->sistema_id = $request->sistema_id;

        if ($equipo->save()){
            return new MTEquipoResource($equipo);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipo = MTEquipo::findOrFail($id);
        return new MTEquipoResource($equipo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $equipo = MTEquipo::findOrFail($id);

        $equipo->nombre = $request->nombre;
        $equipo->descripcion = $request->descripcion;
        $equipo->sistema_id = $request->sistema_id;

        if ($equipo->save()){
            return new MTEquipoResource($equipo);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipo = MTEquipo::findOrFail($id);
        if ($equipo->delete()){
            return new MTEquipoResource($equipo);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTEstadoIncidenciaGEResource;
use App\Models\MTEstadoIncidenciaGE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MTEstadoIncidenciaGEController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTEstadoIncidenciaGE::paginate(100);

        return MTEstadoIncidenciaGEResource::collection($lists);
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
            //'codigo' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTEstadoIncidenciaGE();
        $model->nombre = $request->nombre;
        $model->codigo = Str::random(10);
        //$model->codigo = $request->codigo;

        if ($model->save()){
            return new MTEstadoIncidenciaGEResource($model);
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
        $model = MTEstadoIncidenciaGE::findOrFail($id);
        return new MTEstadoIncidenciaGEResource($model);
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
        $model = MTEstadoIncidenciaGE::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->codigo = $request->codigo;

        if ($model->save()){
            return new MTEstadoIncidenciaGEResource($model);
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
        $model = MTEstadoIncidenciaGE::findOrFail($id);

        if ($model->delete()){
            return new MTEstadoIncidenciaGEResource($model);
        }
    }
}

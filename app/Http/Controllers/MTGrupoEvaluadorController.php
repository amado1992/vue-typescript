<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTGrupoEvaluadorResource;
use App\Models\MTGrupoEvaluador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTGrupoEvaluadorController extends Controller
{
    public function grupo_evaluador_proceso($id){
        $lists = MTGrupoEvaluador::with(['experto', 'proceso'])->where('proceso_id', $id)->get();
        return $lists;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'proceso_id' => 'required',
            'mtfichaexperto_id' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTGrupoEvaluador();

        $model->proceso_id  = $request->proceso_id;
        $model->mtfichaexperto_id = $request->mtfichaexperto_id;
        $model->esJefe = $request->esJefe;
        $model->evalUno = $request->evalUno;
        $model->evalDos = $request->evalDos;

        if ($model->save()){
            return new MTGrupoEvaluadorResource($model);
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
        $model = MTGrupoEvaluador::findOrFail($id);
        return new MTGrupoEvaluadorResource($model);
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
        $model = MTGrupoEvaluador::findOrFail($id);

        $model->proceso_id  = $request->proceso_id;
        $model->mtfichaexperto_id = $request->mtfichaexperto_id;
        $model->esJefe = $request->esJefe;
        $model->evalUno = $request->evalUno;
        $model->evalDos = $request->evalDos;

        if ($model->save()){
            return new MTGrupoEvaluadorResource($model);
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
        $model = MTGrupoEvaluador::findOrFail($id);
        if ($model->delete()){
            return new MTGrupoEvaluadorResource($model);
        }
    }
}

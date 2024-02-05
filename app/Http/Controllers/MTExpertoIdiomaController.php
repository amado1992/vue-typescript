<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTExpertoIdiomaHabilidadResource;
use App\Http\Resources\MTExpertoIdiomaResource;
use App\Models\MTExpertoIdioma;
use App\Models\MTExpertoIdiomaHabilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MTExpertoIdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTExpertoIdioma::with(['idioma', 'experto'])->paginate(100);

        return MTExpertoIdiomaResource::collection($lists);
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
            'mtidioma_id' => 'required',
            'mtfichaexperto_id' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTExpertoIdioma();

        $model->mtidioma_id = $request->mtidioma_id;
        $model->mtfichaexperto_id = $request->mtfichaexperto_id;

        if ($model->save()){

            return new MTExpertoIdiomaResource($model);
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
        $model = MTExpertoIdioma::findOrFail($id);
        return new MTExpertoIdiomaResource($model);
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
        $model = MTExpertoIdioma::findOrFail($id);

        $model->mtidioma_id = $request->mtidioma_id;
        $model->mtfichaexperto_id = $request->mtfichaexperto_id;
        $model->save();

        $model2 = MTExpertoIdiomaHabilidad::findOrFail($request->expertoidiomahabilidadId);
        $habilidadEval = $request->habilidadEvaluacion;
        $model2->mtexpertoidioma_id = $habilidadEval[0]['mtexpertoidioma_id'];
        $model2->mthabilidad_id = $habilidadEval[0]['mthabilidad_id'];
        $model2->mtevaluacion_id = $habilidadEval[0]['mtevaluacion_id'];

        if ($model2->save()){
            return new MTExpertoIdiomaHabilidadResource($model2);
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
        $model = MTExpertoIdioma::findOrFail($id);
        if ($model->delete()){
            return new MTExpertoIdiomaResource($model);
        }
    }
}

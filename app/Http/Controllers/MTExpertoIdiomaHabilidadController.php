<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTExpertoIdiomaHabilidadResource;
use App\Models\MTExpertoIdiomaHabilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MTExpertoIdiomaHabilidadController extends Controller
{
    public function idiomaHabilidadEvalByExperto($id)
    {
        $lists = DB::table('mtexpertoidiomahabilidad')
            ->join('expertoidioma', 'expertoidioma.id', '=', 'mtexpertoidiomahabilidad.mtexpertoidioma_id')
            ->join('mtevaluaciones', 'mtevaluaciones.id', '=', 'mtexpertoidiomahabilidad.mtevaluacion_id')
            ->join('mthabilidades', 'mthabilidades.id', '=', 'mtexpertoidiomahabilidad.mthabilidad_id')
            ->join('mtfichaexpertos', 'mtfichaexpertos.id', '=', 'expertoidioma.mtfichaexperto_id')
            ->join('mtidiomas', 'mtidiomas.id', '=', 'expertoidioma.mtidioma_id')
            ->select('mtfichaexpertos.nombreApellidos', 'mtfichaexpertos.carnetIdentidad'
            ,'mtidiomas.nombre as idioma', 'mthabilidades.nombre as habilidad', 'mtevaluaciones.nombre as evaluacion', 'mtevaluaciones.codigo as evalcodigo',
             'mtexpertoidiomahabilidad.id as expertoidiomahabilidadId', 'expertoidioma.id as expertoidiomaId',
             'mtexpertoidiomahabilidad.mtexpertoidioma_id', 'mtexpertoidiomahabilidad.mthabilidad_id', 'mtexpertoidiomahabilidad.mtevaluacion_id'
            ,'expertoidioma.mtidioma_id', 'expertoidioma.mtfichaexperto_id')
            ->where('mtexpertoidiomahabilidad.mtexpertoidioma_id', '=', $id)
            ->get();
        return $lists;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTExpertoIdiomaHabilidad::with(['experto_idioma.idioma', 'experto_idioma.experto', 'habilidad', 'evaluacion'])->paginate(100);

        return MTExpertoIdiomaHabilidadResource::collection($lists);
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
            'mtexpertoidioma_id' => 'required',
            'mthabilidad_id' => 'required',
            'mtevaluacion_id' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTExpertoIdiomaHabilidad();
        $model->mtexpertoidioma_id = $request->mtexpertoidioma_id;
        $model->mthabilidad_id = $request->mthabilidad_id;
        $model->mtevaluacion_id = $request->mtevaluacion_id;

        if ($model->save()){

            return new MTExpertoIdiomaHabilidadResource($model);
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
        $model = MTExpertoIdiomaHabilidad::findOrFail($id);
        return new MTExpertoIdiomaHabilidadResource($model);
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
        $model = MTExpertoIdiomaHabilidad::findOrFail($id);
        $model->mtexpertoidioma_id = $request->mtexpertoidioma_id;
        $model->mthabilidad_id = $request->mthabilidad_id;
        $model->mtevaluacion_id = $request->mtevaluacion_id;

        if ($model->save()){

            return new MTExpertoIdiomaHabilidadResource($model);
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
        $model = MTExpertoIdiomaHabilidad::findOrFail($id);
        if ($model->delete()){

            return new MTExpertoIdiomaHabilidadResource($model);
        }
    }
}

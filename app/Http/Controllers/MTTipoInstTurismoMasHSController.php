<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTTipoInstResource;
use App\Http\Resources\MTTipoInstTurismoMasHSResource;
use App\Models\MTTipoInst;
use App\Models\MTTipoInstTurismoMasHS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTTipoInstTurismoMasHSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTTipoInstTurismoMasHS::with(['tipo_instalacion', 'valor'])->paginate(1000);

        return MTTipoInstTurismoMasHSResource::collection($lists);
    }

    public function lists_tipos_instalaciones(){
        $lists = MTTipoInst::paginate(100);

        return MTTipoInstResource::collection($lists);
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
            'mttipoinst_id' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTTipoInstTurismoMasHS();

        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;
        $model->mttipoinst_id = $request->mttipoinst_id;
        $model->mtvalor_id = $request->mtvalor_id;

        if ($model->save()){
            return new MTTipoInstTurismoMasHSResource($model);
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
        $model = MTTipoInstTurismoMasHS::findOrFail($id);
        return new MTTipoInstTurismoMasHSResource($model);
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
        $model = MTTipoInstTurismoMasHS::findOrFail($id);

        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;
        $model->mttipoinst_id = $request->mttipoinst_id;
        $model->mtvalor_id = $request->mtvalor_id;

        if ($model->save()){
            return new MTTipoInstTurismoMasHSResource($model);
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
        $model = MTTipoInstTurismoMasHS::findOrFail($id);
        if ($model->delete()){
            return new MTTipoInstTurismoMasHSResource($model);
        }
    }
}

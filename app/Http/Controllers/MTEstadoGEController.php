<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTEstadoGEResource;
use App\Models\MTEstadoGE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MTEstadoGEController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTEstadoGE::paginate(100);

        return MTEstadoGEResource::collection($lists);
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
            //'codigo' => 'required',
            'abreviatura' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTEstadoGE();
        $model->nombre = $request->nombre;
        //$model->codigo = $request->codigo;
        $model->codigo = Str::random(10);
        $model->abreviatura = $request->abreviatura;

        if ($model->save()){
            return new MTEstadoGEResource($model);
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
        $model = MTEstadoGE::findOrFail($id);
        return new MTEstadoGEResource($model);
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
        $model = MTEstadoGE::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->codigo = $request->codigo;
        $model->abreviatura = $request->abreviatura;

        if ($model->save()){
            return new MTEstadoGEResource($model);
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
        $model = MTEstadoGE::findOrFail($id);

        if ($model->delete()){
            return new MTEstadoGEResource($model);
        }
    }
}

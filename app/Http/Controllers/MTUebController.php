<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTUebResource;
use App\Models\MTUeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTUebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTUeb::with('empresa')->paginate(1000);

        return MTUebResource::collection($lists);
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
            'nombre' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTUeb();
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;
        $model->mtempresa_id = $request->mtempresa_id;

        if ($model->save()){
            return new MTUebResource($model);
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
        $model = MTUeb::findOrFail($id);
        return new MTUebResource($model);
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
        $model = MTUeb::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;
        $model->mtempresa_id = $request->mtempresa_id;

        if ($model->save()){
            return new MTUebResource($model);
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
        $model = MTUeb::findOrFail($id);
        if ($model->delete()){
            return new MTUebResource($model);
        }
    }
}

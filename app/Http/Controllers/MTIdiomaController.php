<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTIdiomaResource;
use App\Models\MTIdioma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTIdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTIdioma::paginate(100);

        return MTIdiomaResource::collection($lists);
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
        $model = new MTIdioma();
        $model->nombre = $request->nombre;
        $model->codigo = $request->codigo;

        if ($model->save()){
            return new MTIdiomaResource($model);
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
        $model = MTIdioma::findOrFail($id);
        return new MTIdiomaResource($model);
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
        $model = MTIdioma::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->codigo = $request->codigo;

        if ($model->save()){
            return new MTIdiomaResource($model);
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
        $model = MTIdioma::findOrFail($id);
        if ($model->delete()){
            return new MTIdiomaResource($model);
        }
    }
}

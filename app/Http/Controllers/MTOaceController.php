<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTOaceResource;
use App\Models\MTOace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTOaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTOace::paginate(100);

        return MTOaceResource::collection($lists);
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
        $model = new MTOace();
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;

        if ($model->save()){
            return new MTOaceResource($model);
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
        $model = MTOace::findOrFail($id);
        return new MTOaceResource($model);
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
        $model = MTOace::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;

        if ($model->save()){
            return new MTOaceResource($model);
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
        $model = MTOace::findOrFail($id);
        if ($model->delete()){
            return new MTOaceResource($model);
        }
    }
}

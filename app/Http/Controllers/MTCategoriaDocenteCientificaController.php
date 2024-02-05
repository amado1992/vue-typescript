<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTCategoriaDocenteCientificaResource;
use App\Models\MTCategoriaDocenteCientifica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTCategoriaDocenteCientificaController extends Controller
{
    /**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index()
    {
        $lists = MTCategoriaDocenteCientifica::paginate(1000);

        return MTCategoriaDocenteCientificaResource::collection($lists);
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
        $model = new MTCategoriaDocenteCientifica();
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;

        if ($model->save()){
            return new MTCategoriaDocenteCientificaResource($model);
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
        $model = MTCategoriaDocenteCientifica::findOrFail($id);
        return new MTCategoriaDocenteCientificaResource($model);
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
        $model = MTCategoriaDocenteCientifica::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;

        if ($model->save()){
            return new MTCategoriaDocenteCientificaResource($model);
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
        $model = MTCategoriaDocenteCientifica::findOrFail($id);
        if ($model->delete()){
            return new MTCategoriaDocenteCientificaResource($model);
        }
    }
}

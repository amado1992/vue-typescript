<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTEmpresaResource;
use App\Models\MTEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTEmpresa::with('osde')->paginate(1000);

        return MTEmpresaResource::collection($lists);
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
        $model = new MTEmpresa();
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;
        $model->mtosde_id = $request->mtosde_id;

        if ($model->save()){
            return new MTEmpresaResource($model);
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
        $model = MTEmpresa::findOrFail($id);
        return new MTEmpresaResource($model);
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
        $model = MTEmpresa::findOrFail($id);
        $model->nombre = $request->nombre;
        $model->descripcion = $request->descripcion;
        $model->mtosde_id = $request->mtosde_id;

        if ($model->save()){
            return new MTEmpresaResource($model);
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
        $model = MTEmpresa::findOrFail($id);
        if ($model->delete()){
            return new MTEmpresaResource($model);
        }
    }
}

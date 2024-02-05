<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTSistemaResource;
use App\Models\MTSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTSistemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sistemas = MTSistema::paginate(100);

        return MTSistemaResource::collection($sistemas);
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
            'descripcion' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
            $sistema = new MTSistema();
            $sistema->nombre = $request->nombre;
            $sistema->descripcion = $request->descripcion;

            if ($sistema->save()){
                return new MTSistemaResource($sistema);
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
        $sistema = MTSistema::findOrFail($id);
        return new MTSistemaResource($sistema);
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
        $sistema = MTSistema::findOrFail($id);
        $sistema->nombre = $request->nombre;
        $sistema->descripcion = $request->descripcion;

        if ($sistema->save()){
            return new MTSistemaResource($sistema);
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
        $sistema = MTSistema::findOrFail($id);
        if ($sistema->delete()){
            return new MTSistemaResource($sistema);
        }
    }
}

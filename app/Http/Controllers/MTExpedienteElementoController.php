<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTExpedienteElementoResource;
use App\Models\MTExpedienteElemento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MTExpedienteElementoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   public function  expedient_process($id){
       $expedient_elements = MTExpedienteElemento::where('proceso_id', $id)->get();
       return $expedient_elements;
   }

   public function download_file($id){
       $expedient_element = MTExpedienteElemento::find($id);
       $file = Storage::download($expedient_element->urlElemento,$expedient_element->nombreElemento);

      // dd($file );
       return $file;
       //return response()->download($file, $expedient_element->nombreElemento);
   }


    public function index()
    {
        $expedient_elements = MTExpedienteElemento::with('proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia')->paginate(100);

        return MTExpedienteElementoResource::collection($expedient_elements);
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
        $files = $request->file('files');
        $expedient = $request->exp;

        $expedient_element = new MTExpedienteElemento();
        $expedient_element->proceso_id = $request->proceso_id;

        if(!empty($files)){
            for($i = 0; $i < count($files); $i++){
                $file = $files[$i];
                $path = Storage::putFile($expedient, $file);

                $expedient_element->nombreElemento = $file->getClientOriginalName();
                $expedient_element->urlElemento = $path;

                $expedient_element->save();
            }
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exp = MTExpedienteElemento::findOrFail($id);
        Storage::delete($exp->urlElemento);
        if ($exp->delete()){
            return new MTExpedienteElementoResource($exp);
        }
    }
}

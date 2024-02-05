<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTEmpresaResource;
use App\Http\Resources\MTFichaExpertoResource;
use App\Http\Resources\MTMunicipioResource;
use App\Models\MTEmpresa;
use App\Models\MTFichaExperto;
use App\Models\MTMunicipio;
use App\Models\MTOsde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTFichaExpertoController extends Controller
{
    public function empresas($id){
        $lists = MTOsde::find($id)->empresas;

        return $lists;
    }
    public function uebs($id){
        $lists = MTEmpresa::find($id)->uebs;

        return MTEmpresaResource::collection($lists);
    }

    public function municipios(){
        $lists = MTMunicipio::paginate(100);

        return MTMunicipioResource::collection($lists);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTFichaExperto::with(['oace', 'municipio', 'categoria_docente_cientifica'])->paginate(1000);

        return MTFichaExpertoResource::collection($lists);
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
        'perfil' => 'required',
        'nombreApellidos' => 'required',
        'carnetIdentidad' => 'required',
        'direccion' => 'required',
        'mtmunicipio_id' => 'required',
        'phone' => 'required',
        'email' => 'required',

        'instalacionLaboral' => 'required',
        'departamentoLaboral' => 'required',
        'emailLaboral' => 'required',
        'annosExperienciaSectorLaboral' => 'required',
        'cargoLaboral' => 'required',
        'mtoace_id' => 'required',

        'tituloAcademico' => 'required',
        'nombreInstitucionAcademica' => 'required',
        'fechaAcademica' => 'required',
        'mtcategdocentecientifica_id' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = new MTFichaExperto();
        $model->perfil = $request->perfil;
        $model->nombreApellidos = $request->nombreApellidos;
        $model->carnetIdentidad = $request->carnetIdentidad;
        $model->direccion = $request->direccion;
        $model->mtmunicipio_id = $request->mtmunicipio_id;
        $model->phone = $request->phone;
        $model->email = $request->email;

        $model->instalacionLaboral = $request->instalacionLaboral;
        $model->departamentoLaboral = $request->departamentoLaboral;
        $model->phoneLaboral = $request->phoneLaboral;
        $model->emailLaboral = $request->emailLaboral;
        $model->paginaWebLaboral = $request->paginaWebLaboral;
        $model->annosExperienciaSectorLaboral = $request->annosExperienciaSectorLaboral;
        $model->cargoLaboral = $request->cargoLaboral;
        $model->mtoace_id = $request->mtoace_id;

        $model->mtueb_id = $request->mtueb_id;
        $model->entidad_id = $request->entidad_id;

        $model->tituloAcademico = $request->tituloAcademico;
        $model->nombreInstitucionAcademica = $request->nombreInstitucionAcademica;
        $model->fechaAcademica = $request->fechaAcademica;
        $model->mtcategdocentecientifica_id = $request->mtcategdocentecientifica_id;

        $model->curriculum = "url_curriculum";

        if ($model->save()){
            return new MTFichaExpertoResource($model);
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
        $model = MTFichaExperto::findOrFail($id);
        return new MTFichaExpertoResource($model);
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
        $model = MTFichaExperto::findOrFail($id);
        $model->perfil = $request->perfil;
        $model->nombreApellidos = $request->nombreApellidos;
        $model->carnetIdentidad = $request->carnetIdentidad;
        $model->direccion = $request->direccion;
        $model->mtmunicipio_id = $request->mtmunicipio_id;
        $model->phone = $request->phone;
        $model->email = $request->email;

        $model->instalacionLaboral = $request->instalacionLaboral;
        $model->departamentoLaboral = $request->departamentoLaboral;
        $model->phoneLaboral = $request->phoneLaboral;
        $model->emailLaboral = $request->emailLaboral;
        $model->paginaWebLaboral = $request->paginaWebLaboral;
        $model->annosExperienciaSectorLaboral = $request->annosExperienciaSectorLaboral;
        $model->cargoLaboral = $request->cargoLaboral;
        $model->mtoace_id = $request->mtoace_id;

        $model->mtueb_id = $request->mtueb_id;
        $model->entidad_id = $request->entidad_id;

        $model->tituloAcademico = $request->tituloAcademico;
        $model->nombreInstitucionAcademica = $request->nombreInstitucionAcademica;
        $model->fechaAcademica = $request->fechaAcademica;
        $model->mtcategdocentecientifica_id = $request->mtcategdocentecientifica_id;

        $model->curriculum = "url_curriculum";

        if ($model->save()){
            return new MTFichaExpertoResource($model);
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
        $model = MTFichaExperto::findOrFail($id);
        if ($model->delete()){
            return new MTFichaExpertoResource($model);
        }
    }
}

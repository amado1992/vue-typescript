<?php

namespace App\Http\Controllers;

use App\Models\MTTipoInst;
use App\Repositories\MTTipoInstRepository;
use Illuminate\Http\Request;

class MTTipoInstController extends Controller
{
    private $mtTipoInstRepository;
    private $mtTipoInst;

    public function __construct(MTTipoInstRepository $mtTipoInstRepository, MTTipoInst $mtTipoInst )
    {
        $this->mtTipoInstRepository = $mtTipoInstRepository;
        $this->mtTipoInst = $mtTipoInst;
    }

    public function index()
    {

        $data = $this->mtTipoInstRepository->listMTTipoInst();
        return $data;
    }


    public function store(Request $request)
    {
        $validate = validator($request->all(), [
            'nombre' => 'required|unique:mttipoinst',


        ]);

        if ($validate->fails())
            redirect()->back()->withInput()->withErrors($validate->errors());
        /** @var User $user */
        $mtTipoInst = $this->mtTipoInstRepository->create($request->all());

        if (!$mtTipoInst) return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);

        return response()->json(['success' => true, 'data' => null, 'msg' => 'Tipo de instalación creada']);
    }

    public function update(Request $request, $id)
    {
        $data = $this->mtTipoInstRepository->updateMTTipoInst($id,$request);
        return $data;
    }

    public function destroy(Request $request, $id)
    {
        $data = $this->mtTipoInstRepository->eliminarMTTipoInst($id,$request);
        return $data;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\MTOsde;
use App\Repositories\MTOsdeRepository;
use Illuminate\Http\Request;

class MTOsdeController extends Controller
{
    private $mtOsdeRepository;
    private $mtOsde;

    public function __construct(MTOsdeRepository $mtOsdeRepository, MTOsde $mtOsde )
    {
        $this->mtOsdeRepository = $mtOsdeRepository;
        $this->mtOsde = $mtOsde;
    }

    public function index()
    {

        $data = $this->mtOsdeRepository->listMTOsde();
        return $data;
    }


    public function store(Request $request)
    {
        $validate = validator($request->all(), [
            'nombre' => 'required|unique:mtosde',
            'codigo' => 'required|unique:mtosde',

        ]);

        if ($validate->fails())
            redirect()->back()->withInput()->withErrors($validate->errors());
        /** @var User $user */
        $mtOsde = $this->mtOsdeRepository->create($request->all());

        if (!$mtOsde) return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);

        return response()->json(['success' => true, 'data' => null, 'msg' => 'Osde creada']);
    }

    public function update(Request $request, $id)
    {
        $data = $this->mtOsdeRepository->updateMTOsde($id,$request);
        return $data;
    }

    public function destroy(Request $request, $id)
    {
        $data = $this->mtOsdeRepository->eliminarMTOsde($id,$request);
        return $data;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\MTInstalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTInstalacionController extends Controller
{
    public  function show (){
        return $instalacion = DB::table('mtinstalacion')->get();
        //return MTInstalacion::with('municipios')->get();
    }
}

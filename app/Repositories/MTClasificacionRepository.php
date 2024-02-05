<?php


namespace App\Repositories;

use App\Models\MTClasificacion;
use App\Repositories\BaseRepository;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTClasificacionRepository
{

    private $mtClasificacion;

    public function __construct(MTClasificacion $mtClasificacion)
    {
        parent::__construct($mtClasificacion);
        $this->mtClasificacion = $mtClasificacion;
    }

    protected $fieldSearchable = [
        'nombre'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return MTClasificacion::class;
    }

    public function listMTClasificacion()
    {

        $mtClasificacion  = $this->mtClasificacion->orderBy('created_at','desc')->get();

        if(!isset($mtClasificacion) || $mtClasificacion==null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay clasificaciones que mostrar']);
        return response()->json($mtClasificacion);

    }

    public function updateMTClasificacion($id, Request $request)
    {
        $mtClasificacion = MTClasificacion::find($id);

        $mtClasificacion->update($request->all());

        return response()->json('Clasificación modificada');
    }

    public function eliminarMTClasificacion($id, Request $request)
    {
        $mtClasificacion = MTClasificacion::find($id);

        $mtClasificacion->delete($request->all());

        return response()->json('Clasificación  eliminada');
    }

    /**
     * Configure the Model
     *
     * @return string
     */

}

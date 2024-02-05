<?php

namespace App\Repositories;

use App\Models\MTDemanda;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTDemandaRepository
 * @package App\Repositories
 * @version June 23, 2021, 9:56 am CDT
 */
class MTDemandaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'instalacion_id',
        'familia_id',
        'producto_id',
        'unidad_medida',
        'mes_id',
        'anno',
        'total_demanda_anual',
        'aprobado',
        'deficit',
        'porciento_aprobado'
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

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MTDemanda::class;
    }


    public function getAllPaginateDemanda($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        $callBackFamilia = EventsUtil::callBack('nombre_familia', 'like', '%' . $filter . '%');
        return $this->model->with([
            'instalacion:id,nombre',
            'familia:id,nombre_familia',
            'producto:id,nombre'
        ])
            ->where('unidad_medida', 'like', '%' . $filter . '%')
            ->where('anno', 'like', '%' . $filter . '%')
            ->where('enero', 'like', '%' . $filter . '%')
            ->where('febrero', 'like', '%' . $filter . '%')
            ->where('marzo', 'like', '%' . $filter . '%')
            ->where('abril', 'like', '%' . $filter . '%')
            ->where('mayo', 'like', '%' . $filter . '%')
            ->where('junio', 'like', '%' . $filter . '%')
            ->where('julio', 'like', '%' . $filter . '%')
            ->where('agosto', 'like', '%' . $filter . '%')
            ->where('septiembre', 'like', '%' . $filter . '%')
            ->where('octubre', 'like', '%' . $filter . '%')
            ->where('noviembre', 'like', '%' . $filter . '%')
            ->where('diciembre', 'like', '%' . $filter . '%')
            ->where('total_demanda_anual', 'like', '%' . $filter . '%')
            ->where('aprobado', 'like', '%' . $filter . '%')
            ->where('deficit', 'like', '%' . $filter . '%')
            ->where('porciento_aprobado', 'like', '%' . $filter . '%')
            ->orWhereHas('instalacion', $callBack)
            ->orWhereHas('familia', $callBackFamilia)
            ->orWhereHas('producto', $callBack)
            ->orderByDesc('anno')
            ->groupBy('anno', 'instalacion_id')
            ->paginate($request['itemsPerPage']);
    }

    public function getDemandas()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }

    public function getDemandasInstalacionRanAnnos($id, $annoInicial, $annoFinal)
    {
        return $this->model->with([
            'instalacion:id,nombre',
            'familia:id,nombre_familia',
            'producto:id,nombre'
        ])
            ->where('anno', '>=', $annoInicial)
            ->where('anno', '<=', $annoFinal)
            ->where('instalacion_id', '=', $id)
            ->orderByDesc('anno')
            ->get();
    }

    public function getProductoId($datos)
    {
        $producto = DB::table('producto')
            ->where('nombre', '=', $datos['nombre'])
            ->select('*')
            ->get()->toArray();
        return $producto[0]->id;
    }

    public function getInstalacionId($datos)
    {
        $producto = DB::table('mtinstalacion')
            ->where('id', '=', $datos['instalacion_id'])
            ->select('*')
            ->get()->toArray();
        return $producto[0]->id;
    }

    public function getDemandasInstalacionAnno($id, $anno)
    {
        return $this->model->with([
            'instalacion:id,nombre',
            'familia:id,nombre_familia',
            'producto:id,nombre'
        ])
            ->where('instalacion_id', '=', $id)
            ->where('anno', '=', $anno)
            ->orderByDesc('created_at')
            ->get();
    }
    public function eliminarDemanda ($id, $anno)
    {
        $producto = DB::table('mtdemanda')
            ->where('instalacion_id', '=', $id)
            ->where('anno', '=', $anno)
            ->delete();
        return $producto;
    }
    public function findIDAnno ($id, $anno){
        $producto = DB::table('mtdemanda')
            ->where('instalacion_id', '=', $id)
            ->where('anno', '=', $anno)
            ->select('*')
            ->get()->toArray();
        return $producto;
    }

    public function getAnnos (){
        $annos = DB::table('mtdemanda')
            ->select('anno')
            ->orderByDesc('anno')
            ->groupBy('anno')
            ->get();
        return $annos;
    }
    //Dashboard
    public function demandaAprobada($request){
        return DB::table('mtdemanda')
        ->select('anno as name')
        ->selectRaw("count(case when aprobado = 1 then 1 end) as y")
        ->where('instalacion_id',$request['instalacion_id'])
        ->where('aprobado',1)
        ->groupBy('anno')
        ->get();
    }

}

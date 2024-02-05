<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\MTCostoCalidadConformidad;
use Illuminate\Support\Facades\DB;

/**
 * Class MTCostoCalidadConformidadRepository
 * @package App\Repositories
 * @version Agosto 12, 2021, 10:08 am CDT
 */
class MTCostoCalidadConformidadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'conformidad_id',
        'reportCostoCalidad_id',
        'importe',
        'acumulado',
        'tipo'
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
        return MTCostoCalidadConformidad::class;
    }

    public function listCostoCalidadConformidad()
    {
        $costocalidad = $this->model->with([
            'costo_conformidad:id,nombre',
            'costo_calidad_report:id,nombre'
        ])->orderBy('created_at', 'desc')->get();

        if (!isset($costocalidad) || $costocalidad == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
        return response()->json($costocalidad);
    }

    /** REPORTES */
    //Mostrar suma total por concepto de todas las entidades
    public function entities_all_costs()
    {
        $entities_all_costs = DB::table('mtcostocalidadconformidades')
            ->join('mtcostocalidadreportes', 'mtcostocalidadreportes.id', '=', 'mtcostocalidadconformidades.reportCostoCalidad_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcostocalidadreportes.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtcostocalidadnoconformidades', 'mtcostocalidadnoconformidades.reportCostoCalidad_id', '=', 'mtcostocalidadreportes.id')
            ->select(
                'mtentidad.nombre',
                DB::raw('SUM(mtcostocalidadconformidades.importe) as total_costo_conformidad'),
                DB::raw('SUM(mtcostocalidadnoconformidades.importe) as total_costo_noconformidad'),
                DB::raw('SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe) as total_costo_calidad'),
                DB::raw('SUM(mtcostocalidadreportes.ventaNetaImporte) as total_venta_neta')
            )
            ->groupBy('mtinstalacion.entidad_id')
            ->get();
        return $entities_all_costs;
    }

    //Mostrar valores de cada indicador a partir de las sumatorias de los totales por entidad.Mo
    public function entities_all_indicators()
    {
        $entities_all_indicators = DB::table('mtcostocalidadconformidades')
            ->join('mtcostocalidadreportes', 'mtcostocalidadreportes.id', '=', 'mtcostocalidadconformidades.reportCostoCalidad_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcostocalidadreportes.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtcostocalidadnoconformidades', 'mtcostocalidadnoconformidades.reportCostoCalidad_id', '=', 'mtcostocalidadreportes.id')
            ->select(
                'mtentidad.nombre',
                DB::raw('SUM(mtcostocalidadconformidades.importe) / (SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe)) as indicator_1'),
                DB::raw('SUM(mtcostocalidadnoconformidades.importe) / (SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe)) as indicator_2'),
                DB::raw('(SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe)) / SUM(mtcostocalidadreportes.ventaNetaImporte) as indicator_3'),
                DB::raw('SUM(mtcostocalidadconformidades.importe) / SUM(mtcostocalidadreportes.ventaNetaImporte) as indicator_4'),
                DB::raw('SUM(mtcostocalidadnoconformidades.importe) / SUM(mtcostocalidadreportes.ventaNetaImporte) as indicator_5')
            )
            ->groupBy('mtinstalacion.entidad_id')
            ->get();
        return $entities_all_indicators;
    }

    //Mostrar por entidad comparar con igual trimestre del año en curso en forma gráfica
    public function entities_all_costs_trimester($request)
    {
        $entities_all_costs_trimester = DB::table('mtcostocalidadconformidades')
            ->join('mtcostocalidadreportes', 'mtcostocalidadreportes.id', '=', 'mtcostocalidadconformidades.reportCostoCalidad_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcostocalidadreportes.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtcostocalidadnoconformidades', 'mtcostocalidadnoconformidades.reportCostoCalidad_id', '=', 'mtcostocalidadreportes.id')
            ->select(
                'mtentidad.nombre',
                DB::raw('SUM(mtcostocalidadconformidades.importe) as total_costo_conformidad'),
                DB::raw('SUM(mtcostocalidadnoconformidades.importe) as total_costo_noconformidad'),
                DB::raw('SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe) as total_costo_calidad'),
                DB::raw('SUM(mtcostocalidadreportes.ventaNetaImporte) as total_venta_neta')
            )
            ->whereBetween('mtcostocalidadreportes.fecha', [$request->fechaComienzo, $request->fechaFin])
            ->groupBy('mtinstalacion.entidad_id')
            ->get();
        return $entities_all_costs_trimester;
    }

    /*Mostrar suma total de subtotales c. conformidad y no conformidad, total costo de calidad y ventas netas de todas las entidades.
 Comparación con igual trimestre del año anterior y trimestres del año en curso*/

    public function entity_all_costs_specify($request)
    {
        $entity_all_costs_total = DB::table('mtcostocalidadconformidades')
            ->join('mtcostocalidadreportes', 'mtcostocalidadreportes.id', '=', 'mtcostocalidadconformidades.reportCostoCalidad_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcostocalidadreportes.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtcostocalidadnoconformidades', 'mtcostocalidadnoconformidades.reportCostoCalidad_id', '=', 'mtcostocalidadreportes.id')
            ->select(
                'mtentidad.nombre',
                DB::raw('SUM(mtcostocalidadconformidades.importe) as total_costo_conformidad'),
                DB::raw('SUM(mtcostocalidadnoconformidades.importe) as total_costo_noconformidad'),
                DB::raw('SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe) as total_costo_calidad'),
                DB::raw('SUM(mtcostocalidadreportes.ventaNetaImporte) as total_venta_neta')
            )
            ->where('mtinstalacion.entidad_id', '=', $request->entidad_id)
            ->whereBetween('mtcostocalidadreportes.fecha', [$request->fechaComienzo, $request->fechaFin])
            ->get();
        return $entity_all_costs_total;
    }

    public function entity_all_costs_total($request)
    {
        $entity_all_costs_total = DB::table('mtcostocalidadconformidades')
            ->join('mtcostocalidadreportes', 'mtcostocalidadreportes.id', '=', 'mtcostocalidadconformidades.reportCostoCalidad_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcostocalidadreportes.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtcostocalidadnoconformidades', 'mtcostocalidadnoconformidades.reportCostoCalidad_id', '=', 'mtcostocalidadreportes.id')
            ->select(
                DB::raw('SUM(mtcostocalidadconformidades.importe) as total_costo_conformidad'),
                DB::raw('SUM(mtcostocalidadnoconformidades.importe) as total_costo_noconformidad'),
                DB::raw('SUM(mtcostocalidadconformidades.importe) + SUM(mtcostocalidadnoconformidades.importe) as total_costo_calidad'),
                DB::raw('SUM(mtcostocalidadreportes.ventaNetaImporte) as total_venta_neta')
            )
            ->whereBetween('mtcostocalidadreportes.fecha', [$request->fechaComienzo, $request->fechaFin])
            ->get();
        return $entity_all_costs_total;
    }
}

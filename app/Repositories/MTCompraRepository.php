<?php


namespace App\Repositories;


use App\Models\MTCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTCompraRepository
{
  private $mtCompra;

  public function __construct(MTCompra $mtCompra)
  {
    $this->mtCompra = $mtCompra;
  }

  protected $fieldSearchable = [
    'fecha_cierre',
    'cantidad_real',
    'cantidad_plan',
    'precio',
    'inventario',
    'total_compras',
    'compras_nacionales',
    'instalacion_id',
    'producto_id',
    'proveedor_id',
    'unidadmedida_id'
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
    return MTCompra::class;
  }

  public function listMTCompra()
  {
    $mtCompra = $this->mtCompra
      ->with(['instalaciones.osdes:id,nombre',
        'instalaciones.provincia:id,nombre',
        'productos.familia:id,nombre_familia',
        'proveedores:id,nombre',
        'unidades:id,nombre'])
      ->orderBy('created_at', 'desc')->get();

    if (!isset($mtCompra) || $mtCompra == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay productos comprados que mostrar']);
    return response()->json($mtCompra);
  }

  public function createMTCompra($request, $instalacionId)
  {
    $data = new MTCompra([
      'fecha_cierre' => $request['fecha_cierre'],
      'cantidad_real' => $request['cantidad_real'],
      'cantidad_plan' => $request['cantidad_plan'],
      'precio' => $request['precio'],
      'inventario' => $request['inventario'],
      'total_compras' => $request['total_compras'],
      'compras_nacionales' => $request['compras_nacionales'],
      'instalacion_id' => $instalacionId,
      'producto_id' => $request['producto_id'],
      'proveedor_id' => $request['proveedor_id'],
      'unidadmedida_id' => $request['unidadmedida_id']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTCompra($id, Request $request)
  {
    $data = MTCompra::find($id);
    $data->fecha_cierre = $request->input('fecha_cierre');
    $data->cantidad_real = $request->input('cantidad_real');
    $data->cantidad_plan = $request->input('cantidad_plan');
    $data->precio = $request->input('precio');
    $data->inventario = $request->input('inventario');
    $data->total_compras = $request->input('total_compras');
    $data->compras_nacionales = $request->input('compras_nacionales');
    $data->producto_id = $request->input('producto_id');
    $data->proveedor_id = $request->input('proveedor_id');
    $data->unidadmedida_id = $request->input('unidadmedida_id');
    $data->save();
    return response()->json($data);
  }

  public function eliminarMTCompra($id)
  {
    MTCompra::destroy($id);
    return response()->json('Gestión de compras eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

  // Reporte -> Comparar por meses el volumen real por familia del producto
  public function compararvolumenrealsxmesxfamiliaproducto(Request $request)
  {
    $real = DB::table('mtcompra')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcompra.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->join('producto', 'producto.id', '=', 'mtcompra.producto_id')
      ->join('familia', 'familia.id', '=', 'producto.familia_id')
      ->join('mtproveedor', 'mtproveedor.id', '=', 'mtcompra.proveedor_id')
      ->join('mtunidadmedida', 'mtunidadmedida.id', '=', 'mtcompra.unidadmedida_id')
      ->select('familia.nombre_familia as familia_producto',
        'mtosde.nombre as osde',
        'mtproveedor.nombre as proveedor',
        DB::raw('SUM(mtcompra.cantidad_real) as cantidad_real'),
        'mtunidadmedida.nombre as unidad_medida')
      ->whereBetween('mtcompra.fecha_cierre', [$request['fecha_inicio'], $request['fecha_fin']])
      ->where('mtosde.id', $request['osde'])
      ->groupBy('producto.familia_id')
      ->get();
    return response()->json(['data' => $real]);
  }

  // Reporte -> Comparar los precios de las compras a las FP con respecto a los precios de la EES al mismo volumen
  public function compararprecioxempresaxvolumenreal(Request $request)
  {
    $precio = DB::table('mtcompra')
      ->join('mtproveedor', 'mtproveedor.id', '=', 'mtcompra.proveedor_id')
      ->join('producto', 'producto.id', '=', 'mtcompra.producto_id')
      ->join('mtunidadmedida', 'mtunidadmedida.id', '=', 'mtcompra.unidadmedida_id')
      ->select('mtproveedor.nombre as proveedor',
        'producto.nombre as producto',
        'mtunidadmedida.nombre as unidad_medida',
        DB::raw('SUM(mtcompra.cantidad_real) as cantidad_real'),
        DB::raw('SUM(mtcompra.precio) as precio'))
      ->where('producto.id', $request['producto'])
      ->groupBy('mtproveedor.nombre')
      ->groupBy('mtcompra.cantidad_real')
      ->get();
    return response()->json(['data' => $precio]);
  }

  // Reporte -> Comparar por territorio el análisis de precios por producto
  public function compararpreciosxproductoxterritorio(Request $request)
  {
    $precio = DB::table('mtcompra')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcompra.instalacion_id')
      ->join('mtprovincia', 'mtprovincia.id', '=', 'mtinstalacion.provincia_id')
      ->join('producto', 'producto.id', '=', 'mtcompra.producto_id')
      ->join('mtproveedor', 'mtproveedor.id', '=', 'mtcompra.proveedor_id')
      ->select('mtprovincia.nombre as provincia',
        'mtproveedor.nombre as proveedor',
        'producto.nombre as producto',
        DB::raw('SUM(mtcompra.precio) as precio'))
      ->where('producto.id', $request['producto'])
      ->groupBy('mtprovincia.nombre')
      ->groupBy('producto_id')
      ->get();
    return response()->json(['data' => $precio]);
  }

  // Reporte -> Mostrar por territorio el volumen de inventario por familia
  public function volumeninventarioxterritorioxfamiliadelproducto(Request $request)
  {
    $inventario = DB::table('mtcompra')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtcompra.instalacion_id')
      ->join('mtprovincia', 'mtprovincia.id', '=', 'mtinstalacion.provincia_id')
      ->join('mtproveedor', 'mtproveedor.id', '=', 'mtcompra.proveedor_id')
      ->join('producto', 'producto.id', '=', 'mtcompra.producto_id')
      ->join('familia', 'familia.id', '=', 'producto.familia_id')
      ->join('mtunidadmedida', 'mtunidadmedida.id', '=', 'mtcompra.unidadmedida_id')
      ->select('mtprovincia.nombre as provincia',
        'mtproveedor.nombre as proveedor',
        'familia.nombre_familia as familia_producto',
        DB::raw('SUM(mtcompra.inventario) as volumen_inventario'),
        'mtunidadmedida.nombre as unidad_medida')
      ->where('mtprovincia.id', $request['provincia'])
      ->groupBy('producto.familia_id')
      ->get();
    return response()->json(['data' => $inventario]);
  }
  //Dashboard
  public function dashboardCantCompras($request)
  {
   return DB::table('mtcompra')
      ->selectRaw('year(fecha_cierre) as name')
      ->selectRaw("count(case when instalacion_id = ? then 1 end) as y",[$request['instalacion_id']])
      ->where('instalacion_id',$request['instalacion_id'])
      ->groupByRaw('year(fecha_cierre)')
      ->get();
  }

}

<?php

namespace App\Repositories;

use App\Models\Producto;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductoRepository
 * @package App\Repositories
 * @version May 28, 2021, 8:35 am CDT
*/

class ProductoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'nombre',
        'cantidad',
        'familia_id'
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
        return Producto::class;
    }

    public function getAllPaginateProducto($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre_familia', 'like', '%' . $filter . '%');
        return $this->model->with([
            'familia:id,nombre_familia'
        ])
            ->where('codigo', 'like', '%' . $filter . '%')
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('cantidad', 'like', '%' . $filter . '%')
            ->orWhereHas('familia', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }

    public function getAllPaginateProductoFamilia($request)
    {
        return $this->model->with([
            'familia:id,nombre_familia'
        ])
            ->where('familia_id', '=', $request['id'])
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }


  public function getProductos()
  {
    return DB::table('producto')
      ->orderBy('producto.created_at', 'desc')
      ->select('*')
      ->get();
  }

}

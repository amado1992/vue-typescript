<?php

namespace App\Repositories;

use App\Models\MTInstalacion;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTInstalacionRepository
 * @package App\Repositories
 * @version June 8, 2021, 10:35 am CDT
*/

class MTInstalacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'provincia_id',
        'osde_id',
        'complejo',
        'tipoInst_id',
        'categoria_id',
        'cadHotelera_id',
        'tomo',
        'folio',
        'registro',
        'fecha_registro',
        'direccion',
        'correo_electronico',
        'telefono'
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
        return MTInstalacion::class;
    }

    public function getAllPaginateInstalacion($request)
    {
        $filter = $request['search'];
        //$callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'provincia:id,nombre',
            'osdes:id,nombre',
            'tipoinst:id,nombre',
            'categorias:id,categoria_instalacion',
            'cadHotelera:id,cadena_hotelera'
            //'entidades:id,nombre',
            //'municipio:id,nombre',
        ])
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('tomo', 'like', '%' . $filter . '%')
            ->where('folio', 'like', '%' . $filter . '%')
            //->where('registro', 'like', '%' . $filter . '%')
            ->where('codigo', 'like', '%' . $filter . '%')
            //->orWhereHas('entidades', $callBack)
            //->orWhereHas('municipio', $callBack)
            //->orWhereHas('tipoinst', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }

    public function getInstalaciones()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }

  public function getInstalacionOsde($id)
  {
    $instalaciones  = $this->model
      ->with('entidades:id,osde_id',
        'entidades.osde:id,nombre',
      )
      ->where('id', $id)->get();

    if(!isset($instalaciones) || $instalaciones == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resgistros que mostrar']);
    return $instalaciones;
  }
}

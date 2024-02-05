<?php


namespace App\Repositories;

use App\Models\MTDisponibilidadHabitaciones;
use App\Models\MTEntidad;
use App\Repositories\BaseRepository;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use \stdClass;

class MTDisponibilidadHabitacionesRepository
{
    private $mtDisponibilidadHabitaciones;

    public function __construct(MTDisponibilidadHabitaciones $mtDisponibilidadHabitaciones)
    {
        $this->mtDisponibilidadHabitaciones = $mtDisponibilidadHabitaciones;
    }

    protected $fieldSearchable = [
        'cant_habitaciones_nd',
        'entidad_id',
        'causa_id',
        'clasificacion_id'
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
        return MTDisponibilidadHabitaciones::class;
    }

    public function listMTDisponibilidadHabitaciones(Request $request)
    {
        $data = $request->all();
        $mtDisponibilidadHabitaciones = $this->mtDisponibilidadHabitaciones->with([
            'entidades:id,nombre',
            'causas:id,nombre',
            'clasificaciones:id,nombre'
        ])->where('entidad_id', '=', $data['entidades']['id'])->orderBy('created_at', 'desc')->get();

        if (!isset($mtDisponibilidadHabitaciones) || $mtDisponibilidadHabitaciones == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay habitaciones disponibles que mostrar']);
        return response()->json($mtDisponibilidadHabitaciones);
    }

    public function updateMTDisponibilidadHabitaciones($id, Request $request)
    {
        $mtDisponibilidadHabitaciones = MTDisponibilidadHabitaciones::find($id);

        $mtDisponibilidadHabitaciones->update($request->all());
        print_r($mtDisponibilidadHabitaciones);
        exit();

        return response()->json('Habitación disponible modificada');
    }

    public function eliminarMTDisponibilidadHabitaciones($id, Request $request)
    {
        $mtDisponibilidadHabitaciones = MTDisponibilidadHabitaciones::find($id);

        $mtDisponibilidadHabitaciones->delete($request->all());

        return response()->json('Habitación disponible eliminada');
    }

    public function listMayorIncidencia()
    {

        $mtDisponibilidadHabitaciones = $this->mtDisponibilidadHabitaciones->with([
            'entidades:id,nombre',
            'causas:id,nombre',
            'clasificaciones:id,nombre'
        ])->orderBy('entidad_id')->orderBy('cant_habitaciones_nd', 'desc')->get();

        $entidades = MTEntidad::all();
        $respuesta = [];
        foreach ($entidades as $ent) {
            array_push($respuesta, $this->getMaxDisponibilityByEntityId($mtDisponibilidadHabitaciones, $ent->id));
        }
        if (!isset($respuesta) || $respuesta == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay habitaciones disponibles que mostrar']);
        return response()->json($respuesta);
    }

    public function existe($arreglo, $elemento)
    {
        if (count($arreglo) != 0) {
            foreach ($arreglo as $a) {
                if ($a->nombre == $elemento)
                    return true;
            }
        }

        return false;
    }

    public function ultimaFila($arreglo)
    {
        $clasificacion[] = new stdClass();
        $clasificacion[0]->nombre = 'Total';
        $clasificacion[0]->mtto = 0;
        $clasificacion[0]->reparacion = 0;
        $clasificacion[0]->reposicion = 0;
        foreach ($arreglo as $a) {
            $clasificacion[0]->mtto += $a->mtto;
            $clasificacion[0]->reparacion += $a->reparacion;
            $clasificacion[0]->reposicion += $a->reposicion;
        }
        return $clasificacion[0];
    }

    public function listHabitacionesNoDisponibles(Request $request)
    {
        $data = $request->all();
        $result = [];
        $mtDisponibilidadHabitaciones = $this->mtDisponibilidadHabitaciones->with([
            'entidades:id,nombre',
            'causas:id,nombre',
            'clasificaciones:id,nombre'
        ])->where('entidad_id', '=', $data['entidades']['id'])->orderBy('created_at', 'desc')->get();

        $clasificaciones = DB::table('mtclasificacion')->get();
        $length = count($mtDisponibilidadHabitaciones);
        $j = 0;
        $nombre = '';
        for ($i = 0; $i < $length; $i++) {
            $clasificacion[] = new stdClass();
            $clasificacion[$j]->nombre = '';
            $clasificacion[$j]->mtto = 0;
            $clasificacion[$j]->reparacion = 0;
            $clasificacion[$j]->reposicion = 0;
            foreach ($mtDisponibilidadHabitaciones as $d) {
                if ($clasificacion[$j]->nombre == '' && $this->existe($result, $d->clasificaciones->nombre) == false) {
                    $clasificacion[$j]->nombre = $d->clasificaciones->nombre;
                    if ($d->causas->nombre == 'Mtto. imprevisto')
                        $clasificacion[$j]->mtto = $d->cant_habitaciones_nd;
                    else if ($d->causas->nombre == 'Reparación Capital')
                        $clasificacion[$j]->reparacion = $d->cant_habitaciones_nd;
                    else
                        $clasificacion[$j]->reposicion = $d->cant_habitaciones_nd;
                } else if ($clasificacion[$j]->nombre == $d->clasificaciones->nombre && $this->existe($result, $d->clasificaciones->nombre) == false) {
                    if ($d->causas->nombre == 'Mtto. imprevisto')
                        $clasificacion[$j]->mtto = $d->cant_habitaciones_nd;
                    else  if ($d->causas->nombre == 'Reparación Capital')
                        $clasificacion[$j]->reparacion = $d->cant_habitaciones_nd;
                    else
                        $clasificacion[$j]->reposicion = $d->cant_habitaciones_nd;
                }
            }
            if ($clasificacion[$j]->nombre != $nombre && $clasificacion[$j]->nombre != '') {
                array_push($result, $clasificacion[$j]);
                $nombre = $clasificacion[$j]->nombre;
                $j++;
            }
        }

        $ultimaFila = $this->ultimaFila($result);
        array_push($result, $ultimaFila);
        if (!isset($result) || $result == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay habitaciones disponibles que mostrar']);
        return response()->json($result);
    }

    private function getMaxDisponibilityByEntityId($elements, $entityId)
    {
        $value = 0;
        $maxElement = null;


        foreach ($elements as $element) {
            if (($element->entidad_id == $entityId) && ($element->cant_habitaciones_nd >= $value)) {
                $value = $element->cant_habitaciones_nd;
                $maxElement = $element;
            }
        }
        return $maxElement;
    }

    public function TotalCantidadHabitaciones($identidad)
    {
        return DB::table('mtinstalacion')->where('entidad_id', '=', $identidad)->count();
    }

    public function SumaDisponibilidad($identidad)
    {
        $suma = 0;
        $result = 0;
        $disponiblidades = DB::table('mtdisponibilidadhabitaciones')->where('entidad_id', '=', $identidad)->get();
        foreach ($disponiblidades as $d) {
            $suma += $d->cant_habitaciones_nd;
        }
        $result = $this->TotalCantidadHabitaciones($identidad) - $suma;
        return $result;
    }

    public function Porciento($identidad)
    {
        $result = 0;
        if ($this->SumaDisponibilidad($identidad) != 0 && $this->TotalCantidadHabitaciones($identidad) != 0)
            $result = $this->SumaDisponibilidad($identidad) * 100 / $this->TotalCantidadHabitaciones($identidad);
        return $result;
    }

    /**
     * @return array
     */
    public function ListPorcientoEntidades()
    {
        $result = [];
        $entidad[0] = new stdClass();
        $entidad[0]->nombre = '';
        $entidad[0]->disponibilidad = 0;
        $entidades = DB::table('mtentidad')->get();
        foreach ($entidades as $e) {
            $i = 0;
            $entidad[$i] = new stdClass();
            $entidad[$i]->name = '';
            $entidad[$i]->y = 0;
            if ($this->Porciento($e->id) < 95 || $this->Porciento($e->id) == 95) {
                $entidad[$i]->name = $e->nombre;
                $entidad[$i]->y = $this->SumaDisponibilidad($e->id);
                array_push($result, $entidad[$i]);
            }
        }
        if (!isset($result) || $result == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay habitaciones disponibles que mostrar']);
        return response()->json($result);
    }

    /**
     * Configure the Model
     *
     * @return string
     */
}

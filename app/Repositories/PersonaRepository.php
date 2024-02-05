<?php

namespace App\Repositories;

use App\Models\Persona;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class PersonaRepository
 * @package App\Repositories
 * @version April 27, 2020, 5:55 pm UTC
*/

class PersonaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre_completo',
        'correo',
        'telefono',
        'activo'
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
        return Persona::class;
    }

    public function getActivePersonas($payload)
    {
        return DB::table($this->model->getTable())->where('activo', $payload)->orderByDesc('created_at')->get();
    }
    public function getPersonaTotal()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
    public function genareteCod()
    {
        $obj = $this->model->orderBy('id', 'desc')->first();
        return "per-" . (str_pad((($obj === null) ? 1 : $obj->id + 1), 2, '0', STR_PAD_LEFT));
    }
    public function getNombrePersona()
    {
        return DB::table('event_persona')
            ->orderBy('event_persona.nombre_completo')
            ->select('event_persona.nombre_completo')
            ->get();
    }
    public function getCorreoPersona()
    {
        return DB::table('event_persona')
            ->orderBy('event_persona.correo')
            ->select('event_persona.correo')
            ->get();
    }
}

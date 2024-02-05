<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class Traza
 * @package App\Models
 * @version March 20, 2020, 6:16 pm UTC
 *
 * @property \App\Models\EventNaccion accion
 * @property string ip
 * @property integer accion_id
 * @property integer usuario_id
 * @property integer user_sa_id
 * @property string modelo
 * @property string descripcion
 */
class Traza extends Model
{

    public $table = 'event_traza';
    public $model = 'Traza';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'ip',
        'accion_id',
        'usuario_id',
        'user_sa_id',
        'modelo',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ip' => 'string',
        'accion_id' => 'integer',
        'usuario_id' => 'integer',
        'user_sa_id' => 'integer',
        'modelo' => 'string',
        'descripcion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function accion()
    {
        return $this->belongsTo(Accion::class, 'accion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function usuarioSa()
    {
        return $this->belongsTo(Usuario::class, 'user_sa_id');
    }

    public function saveTraza($ip, $accion_id, $user_sa_id, $modelo, $description)
    {
        if($user_sa_id === null ){
            $user_sa_id = 1;
        }
        try {
            $this->ip = $ip;
            $this->accion_id = $accion_id;
//            $this->usuario_id = $usuario_id;
            $this->user_sa_id = $user_sa_id;
            $this->modelo = $modelo;
            $this->descripcion = $description;
            $this->save();
            Log::info('La traza fue guardada correctamente, perteneciente al usuario => ' . $this->usuario_id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}

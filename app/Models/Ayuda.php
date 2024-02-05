<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ayuda
 * @package App\Models
 * @version March 10, 2020, 7:28 pm UTC
 *
 * @property string topico
 * @property string subtopico
 * @property string consecutivo
 * @property string contenido
 * @property string ruta
 * @property integer usuario_id
 * @property integer modulo
 */
class Ayuda extends Model
{

    public $table = 'event_ayuda';
    public $model = 'Ayuda';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'topico',
        'subtopico',
        'consecutivo',
        'contenido',
        'ruta',
        'usuario_id',
        'contenido'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'topico' => 'string',
        'subtopico' => 'string',
        'contenido' => 'string',
        'consecutivo' => 'string',
        'ruta' => 'string',
        'usuario_id' => 'integer',
        'modulo' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Persona
 * @package App\Models
 * @version April 27, 2020, 5:55 pm UTC
 *
 * @property string nombre_completo
 * @property string correo
 * @property string codigo
 * @property string telefono
 * @property boolean activo
 */
class Persona extends Model
{

    public $table = 'event_persona';
    public $model = 'Persona';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'codigo',
        'nombre_completo',
        'correo',
        'telefono',
        'activo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'nombre_completo' => 'string',
        'correo' => 'string',
        'telefono' => 'string',
        'activo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function user()
    {
        return $this->hasMany(User::class, 'persona_id');
    }


}

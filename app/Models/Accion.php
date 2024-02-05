<?php


namespace App\Models;



use Illuminate\Database\Eloquent\Model;

/**
 * Class Accion
 * @package App\Models
 * @version March 10, 2020, 7:34 pm UTC
 *
 * @property string accion
 * @property string descripcion
 */
class Accion extends Model
{
    public $table = 'event_naccion';
    public $model = 'Acción';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'accion',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'accion' => 'string',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function traza()
    {
        return $this->hasMany(Traza::class, 'accion_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Familia
 * @package App\Models
 * @version May 27, 2021, 2:42 pm CDT
 *
 * @property string $nombre_familia
 * @property string $descripcion
 */
class Familia extends Model
{

    public $table = 'familia';
    public $model = 'Familia';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'nombre_familia',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre_familia' => 'string',
        'descripcion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function familia()
    {
        return $this->hasMany(Producto::class, 'familia_id');
    }

}

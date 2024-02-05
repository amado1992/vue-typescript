<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * @package App\Models
 * @version May 28, 2021, 8:35 am CDT
 *
 * @property \App\Models\Familia $familia
 * @property string $codigo
 * @property string $nombre
 * @property integer $cantidad
 * @property integer $familia_id

 */
class Producto extends Model
{

    public $table = 'producto';
    public $model = 'Producto';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'codigo',
        'nombre',
        'cantidad',
        'familia_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
        'cantidad' => 'integer',
        'familia_id' => 'integer'
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
    public function familia()
    {
        return $this->belongsTo(Familia::class, 'familia_id');
    }


}

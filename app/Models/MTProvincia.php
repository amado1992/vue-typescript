<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTProvincia
 * @package App\Models
 * @version June 8, 2021, 8:12 am CDT
 *
 * @property \Illuminate\Database\Eloquent\Collection $mtmunicipios
 * @property string $nombre
 */
class MTProvincia extends Model
{

    public $table = 'mtprovincia';
    public $model = 'Provincia';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'nombre'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string'
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
    public function mtmunicipios()
    {
        return $this->hasMany(MTMunicipio::class, 'provincia_id');
    }
}

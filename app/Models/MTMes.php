<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTMes
 * @package App\Models
 * @version June 23, 2021, 9:57 am CDT
 *
 * @property \Illuminate\Database\Eloquent\Collection $mtdemandas
 * @property string $nombre
 */
class MTMes extends Model
{

    public $table = 'mtmes';

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
    public function mtdemandas()
    {
        return $this->hasMany(Mtdemanda::class, 'mes_id');
    }
}

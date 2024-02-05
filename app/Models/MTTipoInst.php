<?php

namespace App\Models;

use App\MTTipoInstTurismoMasHS;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MTTipoInst
 * @package App\Models
 * @version June 8, 2021, 9:41 am CDT
 *
 * @property \Illuminate\Database\Eloquent\Collection $mtinstalacions
 * @property string $activo
 * @property string $nombre
 */
class MTTipoInst extends Model
{

    public $table = 'mttipoinst';
    public $model = 'TipoInstalacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'activo',
        'nombre'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'activo' => 'string',
        'nombre' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
      'activo' => 'required',
      'nombre' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mtinstalacions()
    {
        return $this->hasMany(MTInstalacion::class, 'tipoInst_id');
    }

    public function tipos_instalaciones()
    {
        return $this->hasMany(MTTipoInstTurismoMasHS::class, 'mttipoinst_id');
    }
}

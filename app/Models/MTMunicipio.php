<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTMunicipio
 * @package App\Models
 * @version June 8, 2021, 8:50 am CDT
 *
 * @property \App\Models\MTProvincia $provincia
 * @property \Illuminate\Database\Eloquent\Collection $mtinstalacions
 * @property string $nombre
 * @property integer $provincia_id
 */
class MTMunicipio extends Model
{

    public $table = 'mtmunicipio';
    public $model = 'Municipio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'nombre',
        'provincia_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'provincia_id' => 'integer'
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
    public function provincia()
    {
        return $this->belongsTo(MTProvincia::class, 'provincia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mtinstalacions()
    {
        return $this->hasMany(MTInstalacion::class, 'municipio_id');
    }

    public function proveedores()
    {
        return $this->hasMany(MTProveedor::class, 'municipio_id');
    }

    public function expertos()
    {
        return $this->hasMany('App\Models\MTFichaExperto', 'mtmunicipio_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MTSector
 * @package App\Models
 * @version June 8, 2021, 3:45 pm CDT
 *
 * @property string $nombre
 */
class MTSector extends Model
{

    public $table = 'mtsector';
    public $model = 'Sector';

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


}

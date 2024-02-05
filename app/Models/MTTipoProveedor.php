<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoProveedor extends Model
{
    public $table = 'mttipoproveedor';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',
    ];

    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
    ];

    public static $rules = [
        'nombre' => 'required'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTFamiliaProducto extends Model
{
    public $table = 'mtfamiliaproducto';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',

    ];

    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
    ];

    public static $rules = [
        'nombre' => 'required',
    ];


    public function productos()
    {
        return $this->hasMany(\App\Models\MTProducto::class);
    }

}

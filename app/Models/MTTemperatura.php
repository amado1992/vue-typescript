<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTemperatura extends Model
{
    public $table = 'mttemperatura';

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

    public function almacenes()
    {
        return $this->hasMany(\App\Models\MTAlmacen::class);
    }
}

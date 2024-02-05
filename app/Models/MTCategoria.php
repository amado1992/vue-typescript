<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCategoria extends Model
{
    public $table = 'mtcategoria';

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

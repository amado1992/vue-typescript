<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTRenglon extends Model
{
    public $table = 'mtrenglons';

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


    public function indicador()
    {
        return $this->hasMany(\App\Models\MTIndicador::class);
    }

}

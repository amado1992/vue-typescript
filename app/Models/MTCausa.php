<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCausa extends Model
{
    public $table = 'mtcausa';

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


    public function disponibilidadHabitaciones()
    {
        return $this->hasMany(\App\Models\MTDisponibilidadHabitaciones::class);
    }

}

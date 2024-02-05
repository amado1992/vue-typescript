<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTIndicador extends Model
{
    public $table = 'mtindicadors';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',
        'renglon_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'renglon_id' => 'integer',
    ];
    protected $with = [
        'renglon'
    ];

    public static $rules = [
        'nombre' => 'required',
        'renglon_id' => 'required',
    ];

    public function renglon()
    {
        return $this->belongsTo(\App\Models\MTRenglon::class);
    }
}

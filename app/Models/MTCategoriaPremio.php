<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCategoriaPremio extends Model
{
    public $table = 'mtcategoriapremio';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',
    ];

    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
    ];

    public static $rules = [
        'nombre' => 'required|unique:mtcategoriapremio',
    ];

    public function premios()
    {
        return $this->hasMany(\App\Models\MTPremio::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTLicSanitaria extends Model
{
    public $table = 'mtlic_sanitaria_nomenclador';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'codigo',
        'estado',
    ];

    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'estado' => 'string',
    ];

    public static $rules = [

    ];

    public function indicadores()
    {
        return $this->hasMany(MTIndicadoresLineamiento::class, 'licsanitaria_id');
    }

}

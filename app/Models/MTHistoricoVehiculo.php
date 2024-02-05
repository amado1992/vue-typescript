<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTHistoricoVehiculo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'historico_vehiculos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $guarded  = []; // todos los atributos son $fillable true.

    protected $cast = [
        'id' => 'integer',
        'vehiculo_id' => 'integer',
        'estado' => 'string',
        'estado_fechaInicial' => 'date',
        'estado_fechaFinal' => 'date',
        'cant_accidentes' => 'integer',
        'fecha_accidente' => 'date',
    ];

    protected $rules = [
        'estado' => 'required',
        'vehiculo_id' => 'required',
        'estado_fechaInicial' => 'required',
    ];

    public function mediodetransporte()
    {
        return $this->belongsTo(MTMedioTransporte::class, 'vehiculo_id');
    }
}

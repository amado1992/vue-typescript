<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTInstalacion extends Model
{
    public $table = 'mtinstalacion';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'activo',
        'nombre',
        'codigo',
        'provincia_id',
        'osde_id',
        'complejo',
        'tipoInst_id',
        'categoria_id',
        'cadHotelera_id',
        'tomo',
        'folio',
        'registro',
        'fecha_registro',
        'direccion',
        'correo_electronico',
        'telefono'
    ];

    protected $casts = [
        'id' => 'integer',
        'activo' => 'string',
        'nombre' => 'string',
        'codigo' => 'string',
        'provincia_id' => 'integer',
        'osde_id' => 'integer',
        'complejo' => 'string',
        'tipoInst_id' => 'integer',
        'categoria_id' => 'integer',
        'cadHotelera_id' => 'integer',
        'tomo' => 'integer',
        'folio' => 'integer',
        'registro' => 'integer',
        'fecha_registro' => 'date',
        'direccion' => 'string',
        'correo_electronico' => 'string',
        'telefono' => 'string',
    ];

    public static $rules = [
        'activo' => 'required',
        'nombre' => 'required',
        'codigo' => 'required',
        'provincia_id' => 'required',
        'osde_id' => 'required',
        'tipoInst_id' => 'required',
        'categoria_id' => 'required',
        'cadHotelera_id' => 'required',
        'tomo' => 'required',
        'folio' => 'required',
        'direccion' => 'required',
        'correo_electronico' => 'required',
        'telefono' => 'required',
    ];

    public function provincia()
    {
      return $this->belongsTo(MTProvincia::class, 'provincia_id');
    }

    public function osdes()
    {
      return $this->belongsTo(MTOsde::class, 'osde_id');
    }

    public function categorias()
    {
      return $this->belongsTo(MTCategoriaInstalacion::class, 'categoria_id');
    }

    public function cadHotelera()
    {
      return $this->belongsTo(MTCadenaHotelera::class, 'cadHotelera_id');
    }


    public function entidades()
    {
        return $this->belongsTo(MTEntidad::class, 'entidad_id');
    }

    public function municipio()
    {
        return $this->belongsTo(MTMunicipio::class, 'municipio_id');
    }

    public function tipoinst()
    {
        return $this->belongsTo(MTTipoInst::class, 'tipoInst_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function indicadores()
    {
        return $this->hasMany(MTPremio::class, 'instalacion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mtalmacens()
    {
        return $this->hasMany(MTAlmacen::class, 'instalacion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function compras()
    {
        return $this->hasMany(MTCompra::class, 'instalacion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function premios()
    {
        return $this->hasMany(MTPremio::class, 'instalacion_id');
    }

    public function instalaciones_certificadas()
    {
        return $this->hasMany(MTInstalacion::class, 'instalacion_id');
    }

    public function sistemastecnologicos()
    {
        return $this->hasMany(\App\Models\MTSistemaTecnologico::class, 'instalacion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mediosdetransporte()
    {
        return $this->hasMany(MTMedioTransporte::class, 'instalacion_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planRecape()
    {
        return $this->hasMany(MTPlanRecape::class, 'instalacion_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function eventosHE()
    {
        return $this->hasMany(MTEventoHigienicoEpidemiologico::class, 'instalacion_id');
    }
}

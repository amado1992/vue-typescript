<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MTDemandaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'instalacion_id' => $this->instalacion_id,
            'familia_id' => $this->familia_id,
            'producto_id' => $this->producto_id,
            'unidad_medida' => $this->unidad_medida,
            'mes_id' => $this->mes_id,
            'anno' => $this->anno,
            'total_demanda_anual' => $this->total_demanda_anual,
            'aprobado' => $this->aprobado,
            'deficit' => $this->deficit,
            'porciento_aprobado' => $this->porciento_aprobado,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

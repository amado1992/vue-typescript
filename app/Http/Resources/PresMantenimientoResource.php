<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PresMantenimientoResource extends JsonResource
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
            'descMtto' => $this->descMtto,
            'unidadMedida' => $this->unidadMedida,
            'prespMttoT1' => $this->prespMttoT1,
            'prespMttoT2' => $this->prespMttoT2,
            'prespMttoTotal' => $this->prespMttoTotal,
            'prespMttoC1' => $this->prespMttoC1,
            'prespMttoC2' => $this->prespMttoC2,
            'prespMttoContrat' => $this->prespMttoContrat,
            'realMttoT1' => $this->realMttoT1,
            'realMttoT2' => $this->realMttoT2,
            'realMttoTotal' => $this->realMttoTotal,
            'realMttoC1' => $this->realMttoC1,
            'realMttoC2' => $this->realMttoC2,
            'realMttoContrat' => $this->realMttoContrat,
            'porCientoMttoTot1' => $this->porCientoMttoTot1,
            'porCientoMttoTot2' => $this->porCientoMttoTot2,
            'porCientoMttoTot3' => $this->porCientoMttoTot3,
            'porCientoMttoContrat1' => $this->porCientoMttoContrat1,
            'porCientoMttoContrat2' => $this->porCientoMttoContrat2,
            'porCientoMttoContrat3' => $this->porCientoMttoContrat3,
            'mes' => $this->mes,
            'anno' => $this->anno,
            'entidad' => $this->entidad,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

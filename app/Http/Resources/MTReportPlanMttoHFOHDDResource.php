<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class MTReportPlanMttoHFOHDDResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  Request  $request
   * @return array
   */
  public function toArray($request)
  {
//    return  [
//      'percent_hfohdd'=> $this->porCientoHFOHDD,
//      //'entity_name'=> $this->entidads[0]['nombre'],
//      //'province_name'=>$this->entidads[0]['mtinstalacions'][0]['municipios']['provincia']['nombre']
//    ];

    return $this->porCientoHFOHDD;
  }
}

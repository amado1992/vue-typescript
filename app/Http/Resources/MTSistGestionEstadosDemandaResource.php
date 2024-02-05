<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MTSistGestionEstadosDemandaResource extends ResourceCollection
{
  /**
   * The "data" wrapper that should be applied.
   *
   * @var string
   */
  public static $wrap = 'data';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return parent::toArray($request);


//      $result = $request->(function($element) {
//        return [ 'mail' => $element->id_EstadoDemanda];
//      });

//      $j = ;
//        //return $j[0]['desc_EstadoDemanda'];
//      foreach ($request as $item)
//      {
//                    return [
//          'year'=> $item[0]->desc_EstadoDemanda,
//
//        ];
//      }

//      return [
//          'year'=> $this->desc_EstadoDemanda,
//
//        ];
    }


}

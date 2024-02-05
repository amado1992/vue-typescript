<?php


namespace App\Repositories;

use App\Models\MTCausa;
use App\Repositories\BaseRepository;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTCausaRepository
{

    private $mtCausa;

    public function __construct(MTCausa $mtCausa)
    {
        parent::__construct($mtCausa);
        $this->mtCausa = $mtCausa;
    }

    protected $fieldSearchable = [
        'nombre'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return MTCausa::class;
    }

    public function listMTCausa()
    {

        $mtCausa  = $this->mtCausa->orderBy('created_at','desc')->get();

        if(!isset($mtCausa) || $mtCausa==null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay causas que mostrar']);
        return response()->json($mtCausa);

    }

    public function updateMTCausa($id, Request $request)
    {
        $mtCausa = MTCausa::find($id);

        $mtCausa->update($request->all());

        return response()->json('Causa modificada');
    }

    public function eliminarMTCausa($id, Request $request)
    {
        $mtCausa = MTCausa::find($id);

        $mtCausa->delete($request->all());

        return response()->json('Causa  eliminada');
    }

    /**
     * Configure the Model
     *
     * @return string
     */

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTMesAPIRequest;
use App\Http\Requests\API\UpdateMTMesAPIRequest;
use App\Models\MTMes;
use App\Models\Traza;
use App\Repositories\MTMesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MTMesResource;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTMesController
 * @package App\Http\Controllers\API
 */

class MTMesAPIController extends AppBaseController
{
    /** @var  MTMesRepository */
    private $mTMesRepository;
    /** @var  MTMes */
    private $mes;

    /** @var  Traza */
    private $traza;

    public function __construct(MTMesRepository $mTMesRepo, MTMes $mes, Traza $traza)
    {
        $this->mTMesRepository = $mTMesRepo;
        $this->mes = $mes;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTMes.
     * GET|HEAD /mTMes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $mTMes = $this->mTMesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(MTMesResource::collection($mTMes), 'M T Mes retrieved successfully');
    }

    /**
     * Store a newly created MTMes in storage.
     * POST /mTMes
     *
     * @param CreateMTMesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTMesAPIRequest $request)
    {
        $input = $request->all();

        $mTMes = $this->mTMesRepository->create($input);

        return $this->sendResponse(new MTMesResource($mTMes), 'M T Mes saved successfully');
    }

    /**
     * Display the specified MTMes.
     * GET|HEAD /mTMes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTMes $mTMes */
        $mTMes = $this->mTMesRepository->find($id);

        if (empty($mTMes)) {
            return $this->sendError('M T Mes not found');
        }

        return $this->sendResponse(new MTMesResource($mTMes), 'M T Mes retrieved successfully');
    }

    /**
     * Update the specified MTMes in storage.
     * PUT/PATCH /mTMes/{id}
     *
     * @param int $id
     * @param UpdateMTMesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTMesAPIRequest $request)
    {
        $input = $request->all();

        /** @var MTMes $mTMes */
        $mTMes = $this->mTMesRepository->find($id);

        if (empty($mTMes)) {
            return $this->sendError('M T Mes not found');
        }

        $mTMes = $this->mTMesRepository->update($input, $id);

        return $this->sendResponse(new MTMesResource($mTMes), 'MTMes updated successfully');
    }

    /**
     * Remove the specified MTMes from storage.
     * DELETE /mTMes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MTMes $mTMes */
        $mTMes = $this->mTMesRepository->find($id);

        if (empty($mTMes)) {
            return $this->sendError('M T Mes not found');
        }

        $mTMes->delete();

        return $this->sendSuccess('M T Mes deleted successfully');
    }
    public function get_meses(Request $request)
    {
        try {
            $meses = $this->mTMesRepository->getMeses();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->mes->model]));
            return $this->sendResponse($meses->toArray(), __('messages.app.data_retrieved', ['model' => $this->mes->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mes->model]), $exception->getMessage(), '500');
        }
    }
}

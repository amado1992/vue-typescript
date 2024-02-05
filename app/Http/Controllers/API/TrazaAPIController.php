<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEntidadAPIRequest;
use App\Http\Requests\API\UpdateTrazaAPIRequest;
use App\Models\Traza;
use App\Utils\EventsUtil;
use App\Repositories\TrazaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class TrazaController
 * @package App\Http\Controllers\API
 */
class TrazaAPIController extends AppBaseController
{
    /** @var  TrazaRepository */
    private $trazaRepository;

    private $traza;

    public function __construct(TrazaRepository $trazaRepo, Traza $traza)
    {
        $this->trazaRepository = $trazaRepo;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the Traza.
     * GET|HEAD /trazas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $trazas = $this->trazaRepository->getAllPaginateUser($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->traza->model]));
            return $this->sendResponse($trazas->toArray(), __('messages.app.data_retrieved', ['model' => $this->traza->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->traza->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created Traza in storage.
     * POST /trazas
     *
     * @param CreateEntidadAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEntidadAPIRequest $request)
    {
        try {
            $input = $request->all();

            $traza = $this->trazaRepository->create($input);

            return $this->sendResponse($traza->toArray(), 'Traza saved successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Saved error ', $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified Traza.
     * GET|HEAD /trazas/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            /** @var Traza $traza */
            $traza = $this->trazaRepository->find($id);

            if (empty($traza)) {
                return $this->sendError('Traza not found');
            }

            return $this->sendResponse($traza->toArray(), 'Traza retrieved successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Saved error ', $exception->getMessage(), '500');
        }
    }

    /**
     * Update the specified Traza in storage.
     * PUT/PATCH /trazas/{id}
     *
     * @param int $id
     * @param UpdateTrazaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTrazaAPIRequest $request)
    {
        try {
            $input = $request->all();

            /** @var Traza $traza */
            $traza = $this->trazaRepository->find($id);

            if (empty($traza)) {
                return $this->sendError('Traza not found');
            }

            $traza = $this->trazaRepository->update($input, $id);

            return $this->sendResponse($traza->toArray(), 'Traza updated successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Saved error ', $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified Traza from storage.
     * DELETE /trazas/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        try {
            /** @var Traza $traza */
            $traza = $this->trazaRepository->find($id);

            if (empty($traza)) {
                return $this->sendError('Traza not found');
            }

            $traza->delete();

            return $this->sendSuccess('Traza deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Saved error ', $exception->getMessage(), '500');
        }
    }
    public function trazaTotal(Request $request)
    {
        try {
            $traza = $this->trazaRepository->getTrazaTotal(true);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->traza->model]));
            return $this->sendResponse($traza->toArray(), __('messages.app.data_retrieved', ['model' => $this->traza->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->traza->model]), $exception->getMessage(), '500');
        }
    }
}

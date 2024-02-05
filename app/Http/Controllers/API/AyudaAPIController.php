<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAyudaAPIRequest;
use App\Http\Requests\API\UpdateRenglonAPIRequest;
use App\Models\Ayuda;
use App\Models\Traza;
use App\Repositories\AyudaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class AyudaController
 * @package App\Http\Controllers\API
 */
class AyudaAPIController extends AppBaseController
{
    /** @var  AyudaRepository */
    private $ayudaRepository;

    /** @var  Ayuda */
    private $ayuda;

    /** @var  Traza */
    private $traza;

    public function __construct(AyudaRepository $ayudaRepo, Ayuda $ayuda, Traza $traza)
    {
        $this->ayudaRepository = $ayudaRepo;
        $this->ayuda = $ayuda;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the Ayuda.
     * GET|HEAD /ayudas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $ayudas = $this->ayudaRepository->getAllRuta($request['ruta']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->ayuda->model]));
            return $this->sendResponse($ayudas->toArray(), __('messages.app.data_retrieved', ['model' => $this->ayuda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->ayuda->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created Ayuda in storage.
     * POST /ayudas
     *
     * @param CreateAyudaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAyudaAPIRequest $request)
    {
        try {
            $input = $request->all();
            $input['usuario_id'] = EventsUtil::getUserId();
            $ayuda = $this->ayudaRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->ayuda->model, 'operation' => 'creada']) . ' con id = ' . $ayuda->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->ayuda->model, json_encode($ayuda));
            return $this->sendResponse($ayuda->toArray(), __('messages.app.model_success', ['model' => $this->ayuda->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->ayuda->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified Ayuda.
     * Post
     *
     * @param Request $request
     *
     * @return Response
     */
    public function show(Request $request)
    {
        try {
            /** @var Ayuda $ayuda */

            $ayuda = $this->ayudaRepository->find($request['id']);

            if (empty($ayuda)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->ayuda->model]));
            }

            Log::info(__('messages.app.data_retrieved', ['model' => $this->ayuda->model]) . ' con id = ' . $ayuda->id);
            return $this->sendResponse($ayuda->toArray(), __('messages.app.data_retrieved', ['model' => $this->ayuda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->ayuda->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Update the specified Ayuda in storage.
     * PUT/PATCH /ayudas/{id}
     *
     * @param int $id
     * @param UpdateRenglonAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRenglonAPIRequest $request)
    {
        try {
            $input = $request->all();

            /** @var Ayuda $ayuda */
            $ayuda = $this->ayudaRepository->find($id);

            if (empty($ayuda)) {
                return $this->sendError('Ayuda not found');
            }

            $ayuda = $this->ayudaRepository->update($input, $id);

            Log::info('Ayuda updated successfully');
            return $this->sendResponse($ayuda->toArray(), 'Ayuda updated successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Saved error ', $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified Ayuda from storage.
     * DELETE /ayudas/{id}
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
            /** @var Ayuda $ayuda */
            $ayuda = $this->ayudaRepository->find($id);

            if (empty($ayuda)) {
                return $this->sendError('Ayuda not found');
            }

            $ayuda->delete();

            Log::info('Ayuda delete successfully');
            return $this->sendResponse($ayuda->toArray(), 'Ayuda delete successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Delete error ', $exception->getMessage(), '500');
        }
    }
}

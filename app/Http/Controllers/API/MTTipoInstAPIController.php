<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTTipoInstAPIRequest;
use App\Http\Requests\API\UpdateMTTipoInstAPIRequest;
use App\Models\MTTipoInst;
use App\Models\Traza;
use App\Repositories\MTTipoInstRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTTipoInstController
 * @package App\Http\Controllers\API
 */
class MTTipoInstAPIController extends AppBaseController
{
    /** @var  MTTipoInstRepository */
    private $mTTipoInstRepository;


    /** @var  MTTipoInst */
    private $tipoInsta;

    /** @var  Traza */
    private $traza;

    public function __construct(MTTipoInstRepository $mTTipoInstRepo, MTTipoInst $tipoInsta, Traza $traza)
    {
        $this->mTTipoInstRepository = $mTTipoInstRepo;
        $this->tipoInsta = $tipoInsta;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTTipoInst.
     * GET|HEAD /mTTipoInsts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $tipoInst = $this->mTTipoInstRepository->paginate($request['itemsPerPage'], '*', $request['page'], $request['search']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->tipoInsta->model]));
            return $this->sendResponse($tipoInst->toArray(), __('messages.app.data_retrieved', ['model' => $this->tipoInsta->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->tipoInsta->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTTipoInst in storage.
     * POST /mTTipoInsts
     *
     * @param CreateMTTipoInstAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTTipoInstAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mttipoinst'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->tipoInsta->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            $mTTipoInst = $this->mTTipoInstRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->tipoInsta->model, 'operation' => 'creada']) . ' con id = ' . $mTTipoInst->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->tipoInsta->model, json_encode($mTTipoInst));
            return $this->sendResponse($mTTipoInst->toArray(), __('messages.app.model_success', ['model' => $this->tipoInsta->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->tipoInsta->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTTipoInst.
     * GET|HEAD /mTTipoInsts/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTTipoInst $mTTipoInst */
        $mTTipoInst = $this->mTTipoInstRepository->find($id);

        if (empty($mTTipoInst)) {
            return $this->sendError('M T Tipo Inst not found');
        }

        return $this->sendResponse($mTTipoInst->toArray(), 'M T Tipo Inst retrieved successfully');
    }

    /**
     * Update the specified MTTipoInst in storage.
     * PUT/PATCH /mTTipoInsts/{id}
     *
     * @param int $id
     * @param UpdateMTTipoInstAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTTipoInstAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mttipoinst,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->tipoInsta->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTTipoInst $mTTipoInst */
            $objActualizar = $mTTipoInst = $this->mTTipoInstRepository->find($id);

            if (empty($mTTipoInst)) {
                return $this->sendError('M T Tipo Inst not found');
            }

            $mTTipoInst = $this->mTTipoInstRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTTipoInst];
            Log::info(__('messages.app.model_success', ['model' => $this->tipoInsta->model, 'operation' => 'actualizada']) . ' con id = ' . $mTTipoInst->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->tipoInsta->model, json_encode($objArray));
            return $this->sendResponse($mTTipoInst->toArray(), __('messages.app.model_success', ['model' => $this->tipoInsta->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->tipoInsta->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTTipoInst from storage.
     * DELETE /mTTipoInsts/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTTipoInst $mTTipoInst */
            $mTTipoInst = $this->mTTipoInstRepository->find($id);

            if (empty($mTTipoInst)) {
                return $this->sendError('M T Tipo Inst not found');
            }

            $mTTipoInst->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->tipoInsta->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->tipoInsta->model, json_encode($mTTipoInst));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->tipoInsta->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getTiposInst(Request $request)
    {
        try {
            $tipoInst = $this->mTTipoInstRepository->getTiposInst();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->tipoInsta->model]));
            return $this->sendResponse($tipoInst->toArray(), __('messages.app.data_retrieved', ['model' => $this->tipoInsta->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->tipoInsta->model]), $exception->getMessage(), '500');
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTInstalacionAPIRequest;
use App\Http\Requests\API\UpdateMTInstalacionAPIRequest;
use App\Models\MTInstalacion;
use App\Models\Traza;
use App\Repositories\MTInstalacionRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTInstalacionController
 * @package App\Http\Controllers\API
 */
class MTInstalacionAPIController extends AppBaseController
{
    /** @var  MTInstalacionRepository */
    private $mTInstalacionRepository;

    /** @var  MTInstalacion */
    private $instalacion;

    /** @var  Traza */
    private $traza;

    public function __construct(MTInstalacionRepository $mTInstalacionRepo, MTInstalacion $instalacion, Traza $traza)
    {
        $this->mTInstalacionRepository = $mTInstalacionRepo;
        $this->instalacion = $instalacion;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTInstalacion.
     * GET|HEAD /mTInstalacions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $instalaciones = $this->mTInstalacionRepository->getAllPaginateInstalacion($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->instalacion->model]));
            return $this->sendResponse($instalaciones->toArray(), __('messages.app.data_retrieved', ['model' => $this->instalacion->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->instalacion->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTInstalacion in storage.
     * POST /mTInstalacions
     *
     * @param CreateMTInstalacionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTInstalacionAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtinstalacion',
          'codigo' => 'required|unique:mtinstalacion'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre o código');
          return $this->sendError(__('Existe un registro con ese nombre o código', ['model' => $this->instalacion->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            $mTInstalacion = $this->mTInstalacionRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->instalacion->model, 'operation' => 'creada']) . ' con id = ' . $mTInstalacion->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->instalacion->model, json_encode($mTInstalacion));
            return $this->sendResponse($mTInstalacion->toArray(), __('messages.app.model_success', ['model' => $this->instalacion->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->instalacion->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTInstalacion.
     * GET|HEAD /mTInstalacions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTInstalacion $mTInstalacion */
        $mTInstalacion = $this->mTInstalacionRepository->find($id);

        if (empty($mTInstalacion)) {
            return $this->sendError('M T Instalacion not found');
        }

        return $this->sendResponse($mTInstalacion->toArray(), 'M T Instalacion retrieved successfully');
    }

    /**
     * Update the specified MTInstalacion in storage.
     * PUT/PATCH /mTInstalacions/{id}
     *
     * @param int $id
     * @param UpdateMTInstalacionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTInstalacionAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtinstalacion,nombre,' .$id,
          'codigo' => 'required|unique:mtinstalacion,codigo,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre o código');
          return $this->sendError(__('Existe un registro con ese nombre o código', ['model' => $this->instalacion->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTInstalacion $mTInstalacion */
            $objActualizar = $mTInstalacion = $this->mTInstalacionRepository->find($id);

            if (empty($mTInstalacion)) {
                return $this->sendError('M T Instalacion not found');
            }

            $mTInstalacion = $this->mTInstalacionRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTInstalacion];
            Log::info(__('messages.app.model_success', ['model' => $this->instalacion->model, 'operation' => 'actualizada']) . ' con id = ' . $mTInstalacion->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->instalacion->model, json_encode($objArray));
            return $this->sendResponse($mTInstalacion->toArray(), __('messages.app.model_success', ['model' => $this->instalacion->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->instalacion->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTInstalacion from storage.
     * DELETE /mTInstalacions/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTInstalacion $mTInstalacion */
            $mTInstalacion = $this->mTInstalacionRepository->find($id);

            if (empty($mTInstalacion)) {
                return $this->sendError('M T Instalacion not found');
            }

            $mTInstalacion->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->instalacion->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->instalacion->model, json_encode($mTInstalacion));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->instalacion->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getInstalaciones(Request $request)
    {
        try {
            $instalaciones = $this->mTInstalacionRepository->getInstalaciones();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->instalacion->model]));
            return $this->sendResponse($instalaciones->toArray(), __('messages.app.data_retrieved', ['model' => $this->instalacion->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->instalacion->model]), $exception->getMessage(), '500');
        }
    }

  public function getInstalacionOsde(Request $request)
  {
    try {
      $input = $request->all();
      $id = $input['instalacion_id'];
      $instalaciones = $this->mTInstalacionRepository->getInstalacionOsde($id);

      $result = $instalaciones->transform(function ($item)
      {
        return [
          'instalacion_name'=> $item->nombre,
          'osde_name'=> $item->entidades->osde->nombre,
          'id'=> $item->id,
        ];
      });

      Log::info(__('messages.app.data_retrieved', ['model' => $this->instalacion->model]));
      return $this->sendResponse($result->toArray(), __('messages.app.data_retrieved', ['model' => $this->instalacion->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->instalacion->model]), $exception->getMessage(), '500');
    }
  }
}

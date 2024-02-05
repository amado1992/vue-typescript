<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTMunicipioAPIRequest;
use App\Http\Requests\API\UpdateMTMunicipioAPIRequest;
use App\Models\MTMunicipio;
use App\Models\Traza;
use App\Repositories\MTMunicipioRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Class MTMunicipioController
 * @package App\Http\Controllers\API
 */
class MTMunicipioAPIController extends AppBaseController
{
    /** @var  MTMunicipioRepository */
    private $mTMunicipioRepository;

    /** @var  MTMunicipio */
    private $municipio;

    /** @var  Traza */
    private $traza;

    public function __construct(MTMunicipioRepository $mTMunicipioRepo, MTMunicipio $municipio, Traza $traza)
    {
        $this->mTMunicipioRepository = $mTMunicipioRepo;
        $this->municipio = $municipio;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTMunicipio.
     * GET|HEAD /mTMunicipios
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $municipios = $this->mTMunicipioRepository->getAllPaginateMunicipio($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->municipio->model]));
            return $this->sendResponse($municipios->toArray(), __('messages.app.data_retrieved', ['model' => $this->municipio->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->municipio->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTMunicipio in storage.
     * POST /mTMunicipios
     *
     * @param CreateMTMunicipioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTMunicipioAPIRequest $request)
    {
        Validator::extend('uniqueMunicipioProvincia', function ($attribute, $value, $parameters, $validator) {
          $count = DB::table('mtmunicipio')->where('nombre', $value)
            ->where('provincia_id', $parameters[0])
            ->count();

          return $count === 0;
        });

        $validate = validator($request->all(), [
          'nombre' => "uniqueMunicipioProvincia:{$request->provincia_id}"
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre en esa provincia');
          return $this->sendError(__('Existe un registro con ese nombre en esa provincia', ['model' => $this->municipio->model, 'operation' => 'creada']),'500');
        }

        try {
            $input = $request->all();
            $municipio = $this->mTMunicipioRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->municipio->model, 'operation' => 'creada']) . ' con id = ' . $municipio->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->municipio->model, json_encode($municipio));
            return $this->sendResponse($municipio->toArray(), __('messages.app.model_success', ['model' => $this->municipio->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->municipio->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTMunicipio.
     * GET|HEAD /mTMunicipios/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTMunicipio $mTMunicipio */
        $mTMunicipio = $this->mTMunicipioRepository->find($id);

        if (empty($mTMunicipio)) {
            return $this->sendError('M T Municipio not found');
        }

        return $this->sendResponse($mTMunicipio->toArray(), 'M T Municipio retrieved successfully');
    }

    /**
     * Update the specified MTMunicipio in storage.
     * PUT/PATCH /mTMunicipios/{id}
     *
     * @param int $id
     * @param UpdateMTMunicipioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTMunicipioAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtmunicipio,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->municipio->model, 'operation' => 'actualizada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTMunicipio $mTMunicipio */
            $objActualizar = $mTMunicipio = $this->mTMunicipioRepository->find($id);

            if (empty($mTMunicipio)) {
                return $this->sendError('M T Municipio not found');
            }

            $mTMunicipio = $this->mTMunicipioRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTMunicipio];
            Log::info(__('messages.app.model_success', ['model' => $this->municipio->model, 'operation' => 'actualizada']) . ' con id = ' . $mTMunicipio->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->municipio->model, json_encode($objArray));
            return $this->sendResponse($mTMunicipio->toArray(), __('messages.app.model_success', ['model' => $this->municipio->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->municipio->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTMunicipio from storage.
     * DELETE /mTMunicipios/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTMunicipio $mTMunicipio */
            $mTMunicipio = $this->mTMunicipioRepository->find($id);

            if (empty($mTMunicipio)) {
                return $this->sendError('M T Municipio not found');
            }

            $mTMunicipio->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->municipio->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->municipio->model, json_encode($mTMunicipio));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->municipio->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getMunicipios(Request $request)
    {
        try {
            $municipio = $this->mTMunicipioRepository->getMunicipios();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->municipio->model]));
            return $this->sendResponse($municipio->toArray(), __('messages.app.data_retrieved', ['model' => $this->municipio->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->municipio->model]), $exception->getMessage(), '500');
        }
    }
}

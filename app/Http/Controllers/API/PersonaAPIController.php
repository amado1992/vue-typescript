<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePersonaAPIRequest;
use App\Http\Requests\API\UpdatePersonaAPIRequest;
use App\Models\Persona;
use App\Models\Traza;
use App\Utils\EventsUtil;
use App\Repositories\PersonaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class PersonaController
 * @package App\Http\Controllers\API
 */
class PersonaAPIController extends AppBaseController
{
  /** @var  PersonaRepository */
  private $personaRepository;

  /** @var  Persona */
  private $persona;

  /** @var  Traza */
  private $traza;

  public function __construct(PersonaRepository $personaRepo, Persona $persona, Traza $traza)
  {
    $this->personaRepository = $personaRepo;
    $this->persona = $persona;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the Persona.
   * GET|HEAD /personas
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $personas = $this->personaRepository->paginate($request['itemsPerPage'], '*', $request['page'], $request['search'], [], [['name' => 'nombre_completo', 'value' => 'Superuser']]);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->persona->model]));
      return $this->sendResponse($personas->toArray(), __('messages.app.data_retrieved', ['model' => $this->persona->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->persona->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the Persona.
   * Post /monedas
   *
   * @param Request $request
   * @return Response
   */
  public function personaActivo(Request $request)
  {
    try {
      $personas = $this->personaRepository->getActivePersonas(true);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->persona->model]));
      return $this->sendResponse($personas->toArray(), __('messages.app.data_retrieved', ['model' => $this->persona->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->persona->model]), $exception->getMessage(), '500');
    }
  }

  public function personaNombre(Request $request)
  {
    try {
      $personas = $this->personaRepository->getNombrePersona();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->persona->model]));
      return $this->sendResponse($personas->toArray(), __('messages.app.data_retrieved', ['model' => $this->persona->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->persona->model]), $exception->getMessage(), '500');
    }
  }

  public function personaCorreo(Request $request)
  {
    try {
      $personas = $this->personaRepository->getCorreoPersona();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->persona->model]));
      return $this->sendResponse($personas->toArray(), __('messages.app.data_retrieved', ['model' => $this->persona->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->persona->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Persona in storage.
   * POST /personas
   *
   * @param CreatePersonaAPIRequest $request
   *
   * @return Response
   */
  public function store(CreatePersonaAPIRequest $request)
  {
    try {
      $input = $request->all();
      $input['codigo'] = $this->personaRepository->genareteCod();
      $persona = $this->personaRepository->create($input);
      Log::info(__('messages.app.model_success', ['model' => $this->persona->model, 'operation' => 'creada']) . ' con id = ' . $persona->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->persona->model, json_encode($persona));
      return $this->sendResponse($persona->toArray(), __('messages.app.model_success', ['model' => $this->persona->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->persona->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Persona.
   * GET|HEAD /personas/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    try {
      /** @var Persona $persona */
      $persona = $this->personaRepository->find($id);

      if (empty($persona)) {
        return $this->sendError('Persona not found');
      }
      Log::info(__('messages.app.data_retrieved', ['model' => $this->persona->model]) . ' con id = ' . $persona->id);
      return $this->sendResponse($persona->toArray(), __('messages.app.data_retrieved', ['model' => $this->persona->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->persona->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Update the specified Persona in storage.
   * PUT/PATCH /personas/{id}
   *
   * @param int $id
   * @param UpdatePersonaAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdatePersonaAPIRequest $request)
  {
    try {
      $input = $request->all();

      /** @var Persona $persona */
      $objActualizar = $persona = $this->personaRepository->find($id);

      if (empty($persona)) {
        return $this->sendError('Persona not found');
      }

      $persona = $this->personaRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $persona];
      Log::info(__('messages.app.model_success', ['model' => $this->persona->model, 'operation' => 'actualizada']) . ' con id = ' . $persona->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->persona->model, json_encode($objArray));
      return $this->sendResponse($persona->toArray(), __('messages.app.model_success', ['model' => $this->persona->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->persona->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Persona from storage.
   * DELETE /personas/{id}
   *
   * @param int $id
   * @param Request $request
   *
   * @return Response
   * @throws \Exception
   *
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var Persona $persona */
      $persona = $this->personaRepository->find($id);

      if (empty($persona)) {
        return $this->sendError('Persona not found');
      }

      $persona->delete();
      Log::info(__('messages.app.model_success', ['model' => $this->persona->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->persona->model, json_encode($persona));
      return $this->sendSuccess('Persona deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->persona->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function personaTotal(Request $request)
  {
    try {
      $persona = $this->personaRepository->getPersonaTotal(true);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->persona->model]));
      return $this->sendResponse($persona->toArray(), __('messages.app.data_retrieved', ['model' => $this->persona->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->persona->model]), $exception->getMessage(), '500');
    }
  }
}

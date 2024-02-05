<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUsuarioAPIRequest;
use App\Http\Requests\API\UpdateUsuarioAPIRequest;
use App\Models\Traza;
use App\Models\User;
use App\Models\Usuario;
use App\Repositories\UserRepository;
use App\Repositories\UsuarioRepository;
use App\Utils\Encriptar;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class UsuarioController
 * @package App\Http\Controllers\API
 */
class UsuarioAPIController extends AppBaseController
{
    /** @var  Usuario */
    private $usuario;

    /** @var  Traza */
    private $traza;

    /** @var  User */
    private $user;

    /** @var Encriptar */
    private $crypt;
    /** @var  UsuarioRepository */
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepo, Usuario $usuario, Traza $traza, User $user, Encriptar $crypt)
    {
        $this->usuarioRepository = $usuarioRepo;
        $this->usuario = $usuario;
        $this->traza = $traza;
        $this->user = $user;
        $this->crypt = $crypt;
    }

    /**
     * Display a listing of the Usuario.
     * GET|HEAD /usuarios
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $users = $this->usuarioRepository->getAllPaginateUser($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]));
            return $this->sendResponse($users, __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created Usuario in storage.
     * POST /usuarios
     *
     * @param CreateUsuarioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUsuarioAPIRequest $request)
    {
        $input = $request->all();

        $usuario = $this->usuarioRepository->create($input);

        return $this->sendResponse($usuario->toArray(), 'Usuario saved successfully');
    }

    /**
     * Display the specified Usuario.
     * GET|HEAD /usuarios/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Usuario $usuario */
        $usuario = $this->usuarioRepository->find($id);

        if (empty($usuario)) {
            return $this->sendError('Usuario not found');
        }

        return $this->sendResponse($usuario->toArray(), 'Usuario retrieved successfully');
    }

    /**
     * Update the specified Usuario in storage.
     * PUT/PATCH /usuarios/{id}
     *
     * @param int $id
     * @param UpdateUsuarioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $input = $request->all();
            $objActualizar = $usuario = $this->usuarioRepository->find($id);
            $usuario = $this->usuarioRepository->update($input, $id);
            $this->user = $this->user->find($id);
            $this->usuario = $this->usuario->find($id);
            $this->usuario->rolesSa()->sync($input['role']);
            Log::info(__('messages.app.model_success', ['model' => $this->usuario->model, 'operation' => 'actualizada']) . ' con id = ' . $usuario->id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $usuario, 'permisos'];
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->usuario->model, json_encode($objArray));
            return $this->sendResponse($usuario->toArray(), __('messages.app.model_success', ['model' => 'Usuario', 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->usuario->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified Usuario from storage.
     * DELETE /usuarios/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Usuario $usuario */
        $usuario = $this->usuarioRepository->find($id);

        if (empty($usuario)) {
            return $this->sendError('Usuario not found');
        }

        $usuario->delete();

        return $this->sendSuccess('Usuario deleted successfully');
    }
}

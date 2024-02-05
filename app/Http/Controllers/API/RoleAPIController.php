<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRoleAPIRequest;
use App\Http\Requests\API\UpdateRoleAPIRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Traza;
use App\Models\User;
use App\Utils\EventsUtil;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class RoleController
 * @package App\Http\Controllers\API
 */
class RoleAPIController extends AppBaseController
{
    /** @var  RoleRepository */
    private $roleRepository;

    /** @var  Role */
    private $role;

    /** @var  Permission */
    private $permission;

    /** @var  Traza */
    private $traza;

    public function __construct(RoleRepository $roleRepo, Role $role, Traza $traza, Permission $permission)
    {
        $this->roleRepository = $roleRepo;
        $this->role = $role;
        $this->permission = $permission;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the Role.
     * GET|HEAD /roles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $roles = $this->roleRepository->paginate($request['itemsPerPage'], '*', $request['page'], $request['search']
                , ['permisos:role_has_permissions.permission_id,name'], [['name' => 'primary', 'value' => true]]);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->role->model]));
            return $this->sendResponse($roles->toArray(), __('messages.app.data_retrieved', ['model' => $this->role->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->role->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Display a listing of the Moneda.
     * Post /monedas
     *
     * @param Request $request
     * @return Response
     */
    public function getAllPermission(Request $request)
    {
        try {
            $permission = $this->roleRepository->getAllPermission(true);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->permission->model]));
            return $this->sendResponse($permission->toArray(), __('messages.app.data_retrieved', ['model' => $this->permission->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->permission->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Display a listing of the Medio.
     * Post /medios
     *
     * @param Request $request
     * @return Response
     */
    public function roleActivo(Request $request)
    {
        try {
            $roles = $this->roleRepository->getActiveRole(true);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->role->model]));
            return $this->sendResponse($roles->toArray(), __('messages.app.data_retrieved', ['model' => $this->role->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->role->model]), $exception->getMessage(), '500');
        }
    }
    public function roleNombre(Request $request)
    {
        try {
            $roles = $this->roleRepository->getNombreRol();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->role->model]));
            return $this->sendResponse($roles->toArray(), __('messages.app.data_retrieved', ['model' => $this->role->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->role->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created Role in storage.
     * POST /roles
     *
     * @param CreateRoleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleAPIRequest $request)
    {
        try {
            $input = $request->all();
            $input['codigo'] = $this->roleRepository->genareteCod();
            return DB::transaction(function () use ($input, $request) {
                $role = $this->roleRepository->create($input);
                $this->role = $this->role->find($role['id']);
                $this->role->permisos()->sync($input['permiso']);
                Log::info(__('messages.app.model_success', ['model' => $this->role->model, 'operation' => 'creada']) . ' con id = ' . $this->role->id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->role->model, json_encode($role));
                return $this->sendResponse($role->toArray(), __('messages.app.model_success', ['model' => $this->role->model, 'operation' => 'creada']));
            });
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->role->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified Role.
     * GET|HEAD /roles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            /** @var Role $role */
            $role = $this->roleRepository->find($id);

            if (empty($role)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->role->model]));
            }
            Log::info(__('messages.app.data_retrieved', ['model' => $this->role->model]) . ' con id = ' . $role->id);
            return $this->sendResponse($role->toArray(), __('messages.app.data_retrieved', ['model' => $this->role->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->role->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Update the specified Role in storage.
     * PUT/PATCH /roles/{id}
     *
     * @param int $id
     * @param UpdateRoleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleAPIRequest $request)
    {
        try {
            $input = $request->all();
            /** @var Role $role */
            $objActualizar = $role = $this->roleRepository->find($id);

            if (empty($role)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->role->model]));
            }

            $role = $this->roleRepository->update($input, $id);
            $this->role = $this->role->find($role['id']);
            $this->role->permisos()->sync($input['permiso']);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $role];
            Log::info(__('messages.app.model_success', ['model' => $this->role->model, 'operation' => 'actualizada']) . ' con id = ' . $role->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->role->model, json_encode($objArray));
            return $this->sendResponse($role->toArray(), 'Role updated successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->role->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified Role from storage.
     * DELETE /roles/{id}
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
            /** @var Role $role */
            $role = $this->roleRepository->find($id);

            if (empty($role)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->role->model]));
            }

            $role->delete();
            Log::info(__('messages.app.model_success', ['model' => $this->role->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->role->model, json_encode($role));
            return $this->sendSuccess(__('messages.app.model_success', ['model' => $this->role->model, 'operation' => 'eliminada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->role->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
}

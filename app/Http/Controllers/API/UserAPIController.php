<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Mail\CreateUser;
use App\Mail\PasswordMail;
use App\Mail\ChangePassword;
use App\Models\Traza;
use App\Models\User;
use App\Models\Usuario;
use App\Repositories\UserRepository;
use App\Repositories\UsuarioRepository;
use App\Utils\Encriptar;
use App\Utils\EventsUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
//use Illuminate\Http\Client\Response;
use Response;
use zun\sa\Client;
use zun\sa\exceptions\APIException;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    /** @var  UsuarioRepository */
    private $usuarioRepository;

    /** @var  User */
    private $user;

    /** @var  Usuario */
    private $usuario;

    /** @var  Traza */
    private $traza;

    /** @var Encriptar */
    private $crypt;

    public function __construct(UserRepository $userRepo, User $user, Traza $traza, Encriptar $crypt, UsuarioRepository $usuarioRepository, Usuario $usuario)
    {
        $this->userRepository = $userRepo;
        $this->user = $user;
        $this->usuario = $usuario;
        $this->traza = $traza;
        $this->crypt = $crypt;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAllPaginateUser($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]));
            return $this->sendResponse($users->toArray(), __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $input['codigo'] = $this->userRepository->genareteCod();
            return DB::transaction(function () use ($input, $request) {

                $password = Str::random(16);
                $input['password'] = app('hash')->make($password);
                $input['first_login'] = 1;
                $input['primary'] = false;
                $input['admin'] = false;
                $user = $this->userRepository->create($input);
                $this->user = $this->user->find($user['id']);
                $this->user->roles()->sync($input['role']);

                try {
                    Mail::to($input['email'])->send(new CreateUser($this->user, $password));
                }catch (\Throwable $e){
                    report($e);//No genera un error para la pagina

                }
                //Mail::to($input['email'])->send(new CreateUser($this->user, $password));

                Log::info(__('messages.app.model_success', ['model' => $this->user->model, 'operation' => 'creada']) . ' con id = ' . $user->id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->user->model, json_encode($user));
                return $this->sendResponse($this->user->toArray(), __('messages.app.model_success', ['model' => $this->user->model, 'operation' => 'creada']));
            });

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->user->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            /** @var User $user */
            $user = $this->userRepository->find($id);

            if (empty($user)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->user->model]));
            }
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]) . ' con id = ' . $user->id);
            return $this->sendResponse($user->toArray(), __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $input = $request->all();
            $objActualizar = $user = $this->userRepository->find($id);
            if (empty($user)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->user->model]));
            }
            $input['password'] = ($input['password'] != null) ? app('hash')->make($input['password']) : $objActualizar->password;
            $user = $this->userRepository->update($input, $id);
            $this->user = $this->user->find($id);
            $this->user->roles()->sync($input['role']);
            Log::info(__('messages.app.model_success', ['model' => $this->user->model, 'operation' => 'actualizada']) . ' con id = ' . $user->id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $user];
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->user->model, json_encode($objArray));
            return $this->sendResponse($user->toArray(), __('messages.app.model_success', ['model' => $this->user->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->user->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
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
            /** @var User $user */
            $user = $this->userRepository->find($id);

            if (empty($user)) {
                return $this->sendError(__('messages.app.not_found', ['model' => $this->user->model]));
            }

            $user->delete();
            Log::info(__('messages.app.model_success', ['model' => $this->user->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->user->model, json_encode($user));
            return $this->sendSuccess(__('messages.app.model_success', ['model' => $this->user->model, 'operation' => 'eliminada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->user->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Login in aplication.
     * POST /users
     *
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        try {
            $input = $request->only('username', 'password');
            if ($input['username'] === 'superuser@get.tur.cu' || $input['username'] === 'superuser') {
                $this->user = $this->user->where('email', $input['username'])->orWhere('username', $input['username'])->first();
                if ($this->user === null)
                    return response(array('success' => false, 'message' => 'No hay ningún usuario registrado con dichos datos.'));
                if (!$this->user->activo)
                    return response(array('success' => false, 'message' => 'El usuario está desactivado, contacte a los administradores.'));

                $credentials['email'] = $this->user->email;
                $credentials['username'] = $this->user->username;
                $credentials['password'] = $input['password'];
                $credentials['activo'] = $this->user->activo;

                if (!Auth::attempt($credentials))
                    return response(array('success' => false, 'message' => 'El usuario o la contraseña inválido.'));
                $token = $this->crypt->encrypt(EventsUtil::createToken($input['username'], $input['password']));
                if (!session()->has('user.token'))
                    session()->put('user.token', $token);
                else
                    session(['user.token' => $token]);

                auth()->login($this->user);
                $instalacion = $this->getInstalacion($this->user->instalacion_id);
                $this->user->last_login = now();
                $this->user->token = $token;
                $this->user->save();
                $result = [
                    'token' => $token,
                    'scopes' => $this->user->getAllPermission(),
                    'username' => $this->user->username,
                    'email' => $this->user->email,
                    'instalacion' => $instalacion->original['data'][0]->nombre,
                    'instalacion_id' => $this->user->instalacion_id,
                    //'osde_id' => $instalacion->original['data'][0]->entidad_id,
                    //'municipio_id' => $instalacion->original['data'][0]->municipio_id
                ];
                Log::info(__('El usuario :name se auténtico en el sistema', ['name' => $this->user->username]) . ' con id = ' . $this->user->id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 4, EventsUtil::getUserId(), $this->user->model, json_encode(['texto' => __('El usuario :name se auténtico en el sistema', ['name' => $this->user->username]) . ' con id = ' . $this->user->id]));
                return $this->sendResponse($result, 'El usuario esta logeado');
            } else {
                $client = new Client(env('SA_API'));
//                $client = new Client('http://sa.test.dev.get.tur.cu');
//
                try {
                    $userData = $client->session->login($input['username'], $input['password']);
                } catch (APIException $e) {
                    if ($e->getMessage() === '401 : Usuario no activo') {
                        return response(array('success' => false, 'message' => 'Su usuario no esta activado, contacte con el administrador'));
                    } else {
                        return response(array('success' => false, 'message' => 'Sus credenciales son incorrectas'));
                    }
                } catch (\Throwable $e) {
                    if ($e->getCode() === 0) {
                        return response(array('success' => false, 'message' => 'No hay conexion con el servidor de usuarios ZUNsa'));
                    }
                }
                $nombre = $input['username'];
                $usuario = $this->usuarioRepository->findNombre($nombre);
                $id = $usuario[0]->id;
                $permisos = $this->usuario->getAllPermission($id);

                $token = $this->crypt->encrypt(EventsUtil::createToken($usuario[0]->nombre, $usuario[0]->uuid));
                if (!session()->has('user.token')) {
                    session()->put('user.token', $token);
                    session()->put('user.id', $id);
                } else {
                    session(['user.token' => $token]);
                    session(['user.id' => $id]);
                }
                $result = [
                    'token' => $token,
                    'scopes' => $permisos,
                    'username' => $usuario[0]->nombre,
                    'email' => $usuario[0]->email
                ];

                Log::info(__('El usuario :name se auténtico en el sistema', ['name' => $usuario[0]->nombre]) . ' con id = ' . $usuario[0]->id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 4, EventsUtil::getUserId(), $this->usuario->model, json_encode(['texto' => __('El usuario :name se auténtico en el sistema', ['name' => $usuario[0]->nombre]) . ' con id = ' . $usuario[0]->id]));
                return $this->sendResponse($result, 'El usuario esta logeado');
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Hubo un error autenticandose en el sitema.', $exception->getMessage(), '500');
        }
    }

    /**
     * Logout in aplication.
     * POST /users
     *
     * @param Request $request
     *
     * @return Response
     */
    public
    function logout(Request $request)
    {
        try {
            $user = auth()->user();
            auth()->logout();
            $id = session()->get('user.id');
            session()->remove('user');
            if($user === null){
                $userTemp = $this->usuarioRepository->getUsuarioSa($id);
                Log::info(__('El usuario :name salio del sistema', ['name' => $userTemp[0]->nombre]) . ' con id = ' . $userTemp[0]->id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 5, $userTemp[0]->id, $this->user->model, json_encode(['texto' => __('El usuario :name salio del sistema', ['name' => $userTemp[0]->nombre]) . ' con id = ' . $userTemp[0]->id]));
            } else {
                Log::info(__('El usuario :name salio del sistema', ['name' => $user['username']]) . ' con id = ' . $user['id']);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 5, EventsUtil::getUserId(), $this->user->model, json_encode(['texto' => __('El usuario :name salio del sistema', ['name' => $user['username']]) . ' con id = ' . $user['id']]));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Hubo un error saliendo del sitema.', $exception->getMessage(), '500');
        }
    }

    /**
     * Change password in aplication.
     * POST /users
     *
     * @param Request $request
     *
     * @return Response
     */
    public
    function changePassword(Request $request)
    {
        try {
            $password = $request->only('password');
            $user = auth()->user();
            if ($user->first_login == 1) {
                $user->first_login = 0;
            }
            $token = $this->crypt->encrypt(EventsUtil::createToken($user->username, $password['password']));
            $user->password = app('hash')->make($password['password']);
            $user->token = $token;
            $user->save();
            auth()->login($user);
            Log::info(__('El usuario :name cambio la contraseña', ['name' => $user->username]) . ' con id = ' . $user->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 6, EventsUtil::getUserId(), $this->user->model, __('El usuario :name cambio la contraseña', ['name' => $user->username]) . ' con id = ' . $user->id);
            return $this->sendResponse($token, 'El usuario cambio la contraseña correctamente.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Hubo un error cambiando la contraseña del usuario.', $exception->getMessage(), '500');
        }

    }

    public
    function resetPassword(Request $request)
    {
        try {
            $password = Str::random(16);
            $user = $this->user->find($request['id']);
            $persona = $user->persona()->get();
            $user->password = app('hash')->make($password);
            $user->first_login = 1;
            $user->save();
            Mail::to($persona[0]->correo)->send(new PasswordMail($user, $password));
            Log::info(__('Al usuario :name se le cambio la contraseña', ['name' => $user->username]) . ' con id = ' . $user->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 6, EventsUtil::getUserId(), $this->user->model, __('El usuario :name cambio la contraseña', ['name' => $user->username]) . ' con id = ' . $user->id);
            return $this->sendResponse([], 'Al usuario se le cambio la contraseña correctamente.');
        } catch (\Swift_TransportException $swiftException) {
            Log::error($swiftException->getMessage());
            return $this->sendError('Hubo un error estableciendo la conexión con el servidor de correo.', $swiftException->getMessage(), '500');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Hubo un error cambiando la contraseña del usuario.', $exception->getMessage(), '500');
        }

    }

    public
    function changePass(Request $request)
    {
        try {
            $correo = $request->only('correo');
            $userr = $this->userRepository->getBuscarCorreo($correo['correo']);
            $user = $this->user->find($userr[0]->id);
            $password = Str::random(16);
            $persona = $user->persona()->get();
            $user->password = app('hash')->make($password);
            $user->first_login = 1;
            $user->save();
            Mail::to($correo['correo'])->send(new ChangePassword($password));
            Log::info(__('Al usuario :name se le cambio la contraseña', ['name' => $user->username]) . ' con id = ' . $user->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 6, EventsUtil::getUserId(), $this->user->model, __('El usuario :name cambio la contraseña', ['name' => $user->username]) . ' con id = ' . $user->id);
            return $this->sendResponse([], 'Al usuario se le cambio la contraseña correctamente.');
        } catch (\Swift_TransportException $swiftException) {
            Log::error($swiftException->getMessage());
            return $this->sendError('Hubo un error estableciendo la conexión con el servidor de correo.', $swiftException->getMessage(), '500');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('Hubo un error cambiando la contraseña del usuario.', $exception->getMessage(), '500');
        }

    }

    public
    function usuarioTotal(Request $request)
    {
        try {
            $user = $this->userRepository->getUsuarioTotal(true);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]));
            return $this->sendResponse($user->toArray(), __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }

    public
    function loadFirstLogin(Request $request)
    {
        try {
            $user = $this->userRepository->getloadFirstLogin($request['user']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]));
            return $this->sendResponse($user->toArray(), __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }

    public
    function usuarioNombre(Request $request)
    {
        try {
            $user = $this->userRepository->getNombreUser();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]));
            return $this->sendResponse($user->toArray(), __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }

    public
    function usuarioRolTotal(Request $request)
    {
        try {
            $user = $this->userRepository->getUsuarioRolTotal(true);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->user->model]));
            return $this->sendResponse($user->toArray(), __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }
    public
    function getInstalacion($id)
    {
        try {
            $user = $this->userRepository->getInstalacion($id);
            return $this->sendResponse($user, __('messages.app.data_retrieved', ['model' => $this->user->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->user->model]), $exception->getMessage(), '500');
        }
    }
}

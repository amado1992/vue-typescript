<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGestionMantenimientoAPIRequest;
use App\Http\Requests\API\UpdateGestionMantenimientoAPIRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\MTGestionMantenimiento;
use App\Repositories\GestionMantenimientoRepository;
use App\Repositories\PlanMantenimientoRepository;
use App\Repositories\PresMantenimientoRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

class MTGestionMantenimientoAPIController extends AppBaseController
{
  /** @var  GestionMantenimientoRepository */
  private $gestionMantenimientoRepository;

  /** @var  PlanMantenimientoRepository */
  private $planMantenimientoRepository;

  /** @var  PresMantenimientoRepository */
  private $presMantenimientoRepository;

  /** @var  MTGestionMantenimiento */
  private $gestionMantenimiento;

  public function __construct(GestionMantenimientoRepository $gestionMantenimientoRepo,PlanMantenimientoRepository $planMantenimientoRepo,PresMantenimientoRepository $presMantenimientoRepo, MTGestionMantenimiento $gestionMantenimiento)
  {
    $this->gestionMantenimientoRepository = $gestionMantenimientoRepo;
    $this->planMantenimientoRepository = $planMantenimientoRepo;
    $this->presMantenimientoRepository = $presMantenimientoRepo;
    $this->gestionMantenimiento = $gestionMantenimiento;
  }

  /**
   * Display a listing of the MTGestionMantenimiento.
   * GET|HEAD /gestionMantenimiento
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    $gestionMantenimientos = $this->gestionMantenimientoRepository->all(
      $request->except(['skip', 'limit']),
      $request->get('skip'),
      $request->get('limit')
    );

    return $this->sendResponse($gestionMantenimientos->toArray(), 'Gestion Mantenimientos retrieved successfully');
  }

  /**
   * Store a newly created MTGestionMantenimiento in storage.
   * POST /gestionMantenimientos
   *
   * @param CreateGestionMantenimientoAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateGestionMantenimientoAPIRequest $request)
  {
    $input = $request->all();

    $gestionMantenimiento = $this->gestionMantenimientoRepository->create($input);

    return $this->sendResponse($gestionMantenimiento->toArray(), 'Gestion Mantenimiento saved successfully');
  }

  public function show($id)
  {
    /** @var MTGestionMantenimiento $gestionMantenimiento */
    $gestionMantenimiento = $this->gestionMantenimientoRepository->find($id);

    if (empty($gestionMantenimiento)) {
      return $this->sendError('Gestion Mantenimiento not found');
    }

    return $this->sendResponse($gestionMantenimiento->toArray(), 'Gestion Mantenimiento retrieved successfully');
  }

  /**
   * Update the specified MTGestionMantenimiento in storage.
   * PUT/PATCH /gestionMantenimientos/{id}
   *
   * @param int $id
   * @param UpdateGestionMantenimientoAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateGestionMantenimientoAPIRequest $request)
  {
    $input = $request->all();

    /** @var MTGestionMantenimiento $gestionMantenimiento */
    $gestionMantenimiento = $this->gestionMantenimientoRepository->find($id);

    if (empty($gestionMantenimiento)) {
      return $this->sendError('Gestion Mantenimiento not found');
    }

    $gestionMantenimiento = $this->gestionMantenimientoRepository->update($input, $id);

    return $this->sendResponse($gestionMantenimiento->toArray(), 'MTGestionMantenimiento updated successfully');
  }

  /**
   * Remove the specified MTGestionMantenimiento from storage.
   * DELETE /gestionMantenimientos/{id}
   *
   * @param int $id
   *
   * @throws \Exception
   *
   * @return Response
   */
  public function destroy($id)
  {
    /** @var MTGestionMantenimiento $gestionMantenimiento */
    $gestionMantenimiento = $this->gestionMantenimientoRepository->find($id);

    if (empty($gestionMantenimiento)) {
      return $this->sendError('Gestion Mantenimiento not found');
    }

    $gestionMantenimiento->delete();

    return $this->sendSuccess('Gestion Mantenimiento deleted successfully');
  }

  // Salva los datos de lo anexos
  public function createAnexo(Request $request)
  {
      try {
        $exist = $this->gestionMantenimientoRepository->findExist($request['mantenimiento']);

        if (!$exist) {
          $mtto = $this->gestionMantenimientoRepository->create($request['mantenimiento']);
          $dataTable = $request['datas'];
          if ($request['anexo'] == 2) {
            foreach ($dataTable as $item) {
              Arr::set($item, 'gestionmtto_id', $mtto['id']);
              Arr::set($item, 'hDD', $request['attributos']['hdd']);
              Arr::set($item, 'hFO', $request['attributos']['hfo']);
              Arr::set($item, 'porCientoHFOHDD', $request['attributos']['porcientoH']);
              $this->planMantenimientoRepository->create($item);
            }
            return $this->sendResponse('200', 'Elemento creado satisfactoriamente');
          }
          if ($request['anexo'] == 3) {
            foreach ($dataTable as $item) {
              Arr::set($item, 'gestionmtto_id', $mtto['id']);
              $this->presMantenimientoRepository->create($item);
            }
            return $this->sendResponse('200', 'Elemento creado satisfactoriamente');
          }
        }else
          return $this->sendResponse('500', 'El elemento ya existe');
      }
      catch
        (Exception $exception) {
          Log::error($exception->getMessage());
          return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->gestionMantenimiento->model]), $exception->getMessage(), '500');
        }
  }

  // Devuelve los anexos x instalacion
  public function getAnexosxInstalacion(Request $request)
  {
    try {

      $anexos = $this->gestionMantenimientoRepository->getAnexosxInstalacion($request['id_instalacion']);


      return $this->sendResponse($anexos, 'Anexos get successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->gestionMantenimiento->model]), $exception->getMessage(), '500');
    }
  }

  // Elimina anexos
  public function deleteAnexo($id)
  {
    try {
      $anexo = $this->gestionMantenimientoRepository->delete($id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->gestionMantenimiento->model]));
      return $this->sendResponse($anexo, 'Operadora saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->gestionMantenimiento->model]), $exception->getMessage(), '500');
    }
  }

  // Devuelve los anexos x instalacion
  public function getAnexos2All(Request $request)
  {
    try {
      $anexos = $this->gestionMantenimientoRepository->getAnexos2All();
      $result = $anexos->transform(function ($item)
      {
        return [
          'anno'=> $item['anno'],
          'mes'=> $item['mes'],
          'descripcion'=> $item['descripcion'],
          'instalacion'=> $item->instalacions[0]['nombre'],
          'entidad'=> $item->instalacions[0]['entidades']['nombre'],
          'provincia'=> $item->instalacions[0]['municipio']['provincia']['nombre'],
          'hfohdd'=> $item->planmantenimientos[0]['porCientoHFOHDD'],
        ];
      });

      return $this->sendResponse($result, 'Anexos get successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->gestionMantenimiento->model]), $exception->getMessage(), '500');
    }
  }



}

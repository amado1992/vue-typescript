<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLicSanitariaRequest;
use App\Http\Requests\API\UpdateLicSanitariaRequest;
use App\Models\MTLicSanitaria;
use App\Models\Traza;
use App\Repositories\FamiliaRepository;
use App\Repositories\MTLicSanitariaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTLicSanitariaController
 * @package App\Http\Controllers\API
 */
class MTLicSanitariaController extends AppBaseController
{
    /** @var  MTLicSanitariaRepository */
    private $licSanitariaRepository;

    /** @var  MTLicSanitaria */
    private $licSanitaria;

    /** @var  Traza */
    private $traza;

    public function __construct(MTLicSanitariaRepository $licSanitariaRepo, MTLicSanitaria $licSanitaria, Traza $traza)
    {
        $this->licSanitariaRepository = $licSanitariaRepo;
        $this->traza = $traza;
        $this->licSanitaria = $licSanitaria;
    }

    /**
     * Display a listing of the MTLicSanitaria.
     * GET|HEAD /lic_sanitaria
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        //TODO
    }

    /**
     * Store a newly created MTLicSanitaria in storage.
     * POST /lic_sanitaria
     *
     * @param CreateLicSanitariaRequest $request
     *
     * @return Response
     */
    public function store(CreateLicSanitariaRequest $request)
    {
        //TODO
    }

    /**
     * Display the specified MTLicSanitaria.
     * GET|HEAD /lic_sanitaria/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //TODO
    }

    /**
     * Update the specified MTLicSanitaria in storage.
     * PUT/PATCH /lic_sanitaria/{id}
     *
     * @param int $id
     * @param UpdateLicSanitariaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicSanitariaRequest $request)
    {
        //TODO
    }

    /**
     * Remove the specified MTLicSanitaria from storage.
     * DELETE /lic_sanitaria/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        //TODO
    }
    public function getLicencia(Request $request)
    {
        try {
            $licencias = $this->licSanitariaRepository->getLicencias();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->licSanitaria->model]));
            return $this->sendResponse($licencias->toArray(), __('messages.app.data_retrieved', ['model' => $this->licSanitaria->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->licSanitaria->model]), $exception->getMessage(), '500');
        }
    }
}

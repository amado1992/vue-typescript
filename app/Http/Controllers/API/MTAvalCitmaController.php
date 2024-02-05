<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAvalCitmaRequest;
use App\Http\Requests\API\UpdateAvalCitmaRequest;
use App\Models\MTAvalCitma;
use App\Models\Traza;
use App\Repositories\MTAvalCitmaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTAvalCitmaController
 * @package App\Http\Controllers\API
 */
class MTAvalCitmaController extends AppBaseController
{
    /** @var  MTAvalCitmaRepository */
    private $avalCitmaRepository;

    /** @var  MTAvalCitma */
    private $avalCitma;

    /** @var  Traza */
    private $traza;

    public function __construct(MTAvalCitmaRepository $avalCitmaRepo, MTAvalCitma $avalCitma, Traza $traza)
    {
        $this->avalCitmaRepository = $avalCitmaRepo;
        $this->traza = $traza;
        $this->avalCitma = $avalCitma;
    }

    /**
     * Display a listing of the MTAvalCitma.
     * GET|HEAD /avalcitma
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        //TODO
    }

    /**
     * Store a newly created MTAvalCitma in storage.
     * POST /avalcitma
     *
     * @param CreateAvalCitmaRequest $request
     *
     * @return Response
     */
    public function store(CreateAvalCitmaRequest $request)
    {
        //TODO
    }

    /**
     * Display the specified MTAvalCitma.
     * GET|HEAD /avalcitma/{id}
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
     * Update the specified MTAvalCitma in storage.
     * PUT/PATCH /avalcitma/{id}
     *
     * @param int $id
     * @param UpdateAvalCitmaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAvalCitmaRequest $request)
    {
        //TODO
    }

    /**
     * Remove the specified MTAvalCitma from storage.
     * DELETE /avalcitma/{id}
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
    public function getAval(Request $request)
    {
        try {
            $avales = $this->avalCitmaRepository->getAvales();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->avalCitma->model]));
            return $this->sendResponse($avales->toArray(), __('messages.app.data_retrieved', ['model' => $this->avalCitma->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->avalCitma->model]), $exception->getMessage(), '500');
        }
    }
}

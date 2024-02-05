<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAvalApciRequest;
use App\Http\Requests\API\UpdateAvalApciRequest;
use App\Models\MTAvalApci;
use App\Models\Traza;
use App\Repositories\MTAvalApciRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTAvalApciController
 * @package App\Http\Controllers\API
 */
class MTAvalApciController extends AppBaseController
{
    /** @var  MTAvalApciRepository */
    private $avalApciRepository;

    /** @var  MTAvalApci */
    private $avalApci;

    /** @var  Traza */
    private $traza;

    public function __construct(MTAvalApciRepository $avalApciRepo, MTAvalApci $avalApci, Traza $traza)
    {
        $this->avalApciRepository = $avalApciRepo;
        $this->traza = $traza;
        $this->avalApci = $avalApci;
    }

    /**
     * Display a listing of the MTAvalApci.
     * GET|HEAD /avalapci
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        //TODO
    }

    /**
     * Store a newly created MTAvalApci in storage.
     * POST /avalapci
     *
     * @param CreateAvalApciRequest $request
     *
     * @return Response
     */
    public function store(CreateAvalApciRequest $request)
    {
        //TODO
    }

    /**
     * Display the specified MTAvalApci.
     * GET|HEAD /avalapci/{id}
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
     * Update the specified MTAvalApci in storage.
     * PUT/PATCH /avalapci/{id}
     *
     * @param int $id
     * @param UpdateAvalApciRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAvalApciRequest $request)
    {
        //TODO
    }

    /**
     * Remove the specified MTAvalApci from storage.
     * DELETE /avalapci/{id}
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
            $avales = $this->avalApciRepository->getAvales();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->avalApci->model]));
            return $this->sendResponse($avales->toArray(), __('messages.app.data_retrieved', ['model' => $this->avalApci->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->avalApci->model]), $exception->getMessage(), '500');
        }
    }
}

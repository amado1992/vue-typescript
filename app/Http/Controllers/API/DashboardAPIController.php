<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\Traza;
use App\Repositories\DashboardRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardAPIController extends AppBaseController
{
    /** @var  DashboardRepository */
    private $dashboardRepository;

    /** @var  Traza */
    private $traza;

    public function __construct(DashboardRepository $dashboardRepository, Traza $traza)
    {
        $this->dashboardRepository = $dashboardRepository;
        $this->traza = $traza;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->dashboardRepository->dataDashboard();
            Log::info(__('messages.app.data_retrieved', ['model' => 'Dashboard']));
            return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => 'Dashboard']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => 'Dashboard']), $exception->getMessage(), '500');
        }
    }

}

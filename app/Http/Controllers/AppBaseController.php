<?php

namespace App\Http\Controllers;

use App\Utils\EventsUtil;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{

    public function sendResponse($result, $message)
    {
        return Response::json(EventsUtil::makeResponse($message, $result));
    }

    public function sendError($message, $error, $code = 404)
    {
        return Response::json(EventsUtil::makeError($message, $error), $code);
    }
    public function sendWarning($message, $warning, $code = 400)
    {
        return Response::json(EventsUtil::makeWarning($message, $warning), $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}

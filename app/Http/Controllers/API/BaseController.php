<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method
     * @param $result
     * @param $message
     * @param $status
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $status=200): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}

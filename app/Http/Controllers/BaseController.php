<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return JsonResponse
     */
    public function sendResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response);
    }

    /**
     * return error response.
     *
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
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

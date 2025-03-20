<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Response, JsonResponse};

class ApiBaseController extends Controller
{
    /**
     * handle global success response
     *
     * @param $data
     * @param string|null $message
     * @param int $statusCode
     * @param null| $withCookie
     * @return JsonResponse
     */
    public function successResponse(
        $data,
        ?string $message = null,
        int $statusCode = Response::HTTP_OK,
        $withCookie = null
    ): JsonResponse
    {
        $response = [
            "status" => 'success',
            "message" => $message,
            "data" => $data
        ];

        if ($withCookie) {
            return response()->json(
                $response,
                $statusCode
            )->withCookie($withCookie);
        }

        return response()->json($response, $statusCode);
    }

    /**
     * handle global failed response
     *
     * @param $errors
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function failedResponse(
        $errors,
        string $message = "something went wrong",
        int $statusCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        $response = [
            "status" => 'error',
            "message" => $message,
            "errors" => !blank($errors) ? $errors->getMessage() : []
        ];

        return response()->json($response, $statusCode);
    }
}

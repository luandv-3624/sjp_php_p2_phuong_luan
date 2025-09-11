<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use App\Enums\HttpStatusCode;

class ApiResponse
{
    public static function success($data = [], ?string $message = null, int $code = HttpStatusCode::OK): JsonResponse
    {
        return response()->json([
            'isSuccess' => true,
            'statusCode' => $code,
            'message' => $message ?? __('success'),
            'data' => $data,
        ], $code);
    }

    public static function error(?string $message = null, $errors = [], int $code = HttpStatusCode::INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'isSuccess' => false,
            'statusCode' => $code,
            'message' => $message ?? __('error'),
            'errors' => $errors,
        ], $code);
    }
}

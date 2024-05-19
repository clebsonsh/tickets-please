<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponses
{
    /**
     * @param  array<string, string>  $data
     */
    protected function ok(string $message, array $data = []): JsonResponse
    {
        return $this->success(
            $message,
            $data,
        );
    }

    /**
     * @param  array<string, string>  $data
     */
    protected function success(string $message, array $data = [], int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'message' => $message,
                'data' => $data,
                'status' => $statusCode,
            ],
            $statusCode
        );
    }

    protected function error(string $message, int $statusCode): JsonResponse
    {
        return response()->json(
            [
                'message' => $message,
                'status' => $statusCode,
            ],
            $statusCode
        );
    }
}

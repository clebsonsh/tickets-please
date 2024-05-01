<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponses
{
    protected function ok($message, $data = null)
    {
        return $this->success(
            $message,
            $data ? ['data' => $data] : null,
        );
    }

    protected function success($message, $data = null, $statusCode = Response::HTTP_OK)
    {
        return response()->json($data ?
            [
                'message' => $message,
                ...$data,
                'status' => $statusCode,
            ] : [
                'message' => $message,
                'status' => $statusCode,
            ], $statusCode);
    }

    protected function error($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }
}

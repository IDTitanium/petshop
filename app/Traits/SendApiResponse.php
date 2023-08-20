<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendApiResponse
{
    /**
     * Return a successful JSON response with mixed(object|JsonSerializable) data
     */
    public function sendApiResponse(bool $status, int $code, ?string $message = null, mixed $data = null): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($response, $code);
    }
}

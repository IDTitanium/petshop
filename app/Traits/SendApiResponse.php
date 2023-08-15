<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendApiResponse
{
  /**
   * Return a successful JSON response with mixed(object|JsonSerializable) data
   *
   * @param int $code
   * @param string|null $message
   * @param mixed $data
   * @return JsonResponse
   */
  public function sendApiResponse(bool $status, int $code, string $message = null, $data = null) : JsonResponse {

    $response = [
      'status' => $status,
      'message' => $message,
      'data' => $data
    ];
    return response()->json($response, $code);
  }
}

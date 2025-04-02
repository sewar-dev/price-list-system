<?php

declare(strict_types=1);

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;

/**
 * Returns a HTTP JSON response.
 *
 * @param  array|Arrayable|JsonSerializable|null  $data
 */
function sendResponse(
    bool   $status  = true,
    string $message = 'OK',
    array|Arrayable|JsonSerializable|null $data = null,
    int $status_code = 200
): JsonResponse {
    $response_data = [
        'success' => $status,
        'message' => $message,
        'data' => $data,
        'status_code' => $status_code,
    ];

    return response()->json(
        $response_data,
        $status_code
    );
}

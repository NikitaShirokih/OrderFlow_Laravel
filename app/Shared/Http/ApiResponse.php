<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function success(mixed $data = [], string $message = 'success', array $meta = []): JsonResponse
    {
        if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            $data = $data->response()->getData(true)['data'] ?? [];
        }

        if (is_array($data) && array_key_exists('data', $data) && array_key_exists('meta', $data)) {
            $meta = array_merge($data['meta'], $meta);
            $data = $data['data'];
        }

        return response()->json([
            'data' => $data,
            'meta' => self::meta($meta),
            'message' => $message,
        ]);
    }

    public static function error(string $message, int $status = 400, array $errors = [], array $meta = []): JsonResponse
    {
        return response()->json([
            'data' => [
                'errors' => $errors,
            ],
            'meta' => self::meta($meta),
            'message' => $message,
        ], $status);
    }

    public static function paginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }

    private static function meta(array $meta = []): array
    {
        return array_merge([
            'timestamp' => now()->toISOString(),
            'version' => 'v1',
        ], $meta);
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait AdminResponse
{
    private function paginated(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    private function single(mixed $data): array
    {
        return [
            'data' => $data,
            'meta' => [],
        ];
    }

    private function perPage(array $filters): int
    {
        return min(max((int) ($filters['per_page'] ?? 25), 1), 100);
    }
}

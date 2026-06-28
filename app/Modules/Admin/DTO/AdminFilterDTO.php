<?php

declare(strict_types=1);

namespace App\Modules\Admin\DTO;

class AdminFilterDTO
{
    public function __construct(
        private readonly array $filters = []
    ) {
    }

    public static function fromQuery(array $filters): self
    {
        return new self($filters);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->filters[$key] ?? $default;
    }

    public function perPage(): int
    {
        return min(max((int) ($this->filters['per_page'] ?? 25), 1), 100);
    }
}

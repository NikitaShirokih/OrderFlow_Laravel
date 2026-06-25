<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

final class ResourceNotFoundException extends DomainException
{
    public function __construct(
        private readonly string $resource,
        private readonly string|int $identifier,
    ) {
        parent::__construct('Запрашиваемый ресурс не найден.');
    }

    public function errorCode(): string
    {
        return 'resource_not_found';
    }

    public function context(): array
    {
        return [
            'resource' => $this->resource,
            'identifier' => $this->identifier,
        ];
    }
}

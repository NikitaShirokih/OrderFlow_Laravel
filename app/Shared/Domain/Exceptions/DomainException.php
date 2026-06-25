<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use RuntimeException;

abstract class DomainException extends RuntimeException
{
    public function errorCode(): string
    {
        return 'domain_error';
    }

    /**
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return [];
    }
}

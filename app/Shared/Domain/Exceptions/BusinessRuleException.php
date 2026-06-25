<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

final class BusinessRuleException extends DomainException
{
    /**
     * @param  array<string, mixed>  $details
     */
    public function __construct(
        string $message,
        private readonly string $codeName = 'business_rule_violation',
        private readonly array $details = [],
    ) {
        parent::__construct($message);
    }

    public function errorCode(): string
    {
        return $this->codeName;
    }

    public function context(): array
    {
        return $this->details;
    }
}

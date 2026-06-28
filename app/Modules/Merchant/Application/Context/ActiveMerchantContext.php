<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Application\Context;

use App\Modules\Merchant\Domain\Models\Merchant;

class ActiveMerchantContext
{
    private ?Merchant $merchant = null;

    public function set(Merchant $merchant): void
    {
        $this->merchant = $merchant;
    }

    public function get(): Merchant
    {
        if (!$this->merchant) {
            throw new \RuntimeException('Active merchant not set');
        }

        return $this->merchant;
    }

    public function has(): bool
    {
        return $this->merchant !== null;
    }
}
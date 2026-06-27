<?php

declare(strict_types=1);

namespace App\Enums;

enum MerchantRole: string
{
    case OWNER = 'owner';
    case MANAGER = 'manager';
    case SUPPORT = 'support';
}
<?php

declare(strict_types=1);

namespace App\Modules\Orders\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case RESERVED = 'reserved';
    case PAID = 'paid';
    case CANCELED = 'canceled';
}

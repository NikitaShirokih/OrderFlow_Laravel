<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Enums;

enum ProductStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
}

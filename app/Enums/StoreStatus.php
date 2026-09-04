<?php

declare(strict_types=1);

namespace App\Enums;

enum StoreStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case SUSPENDED = 'suspended';
}

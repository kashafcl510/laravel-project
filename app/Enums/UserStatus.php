<?php

namespace App\Enums;

enum UserStatus: int
{
    case DENIED = 0;
    case SUSPENDED = 1;
    case APPROVED = 2;

    public function label(): string
    {
        return match($this) {
            self::DENIED => 'Denied',
            self::SUSPENDED => 'Suspended',
            self::APPROVED => 'Approved',
        };
    }
}

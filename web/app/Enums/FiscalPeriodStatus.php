<?php

namespace App\Enums;

enum FiscalPeriodStatus: string
{
    case OPEN = 'OPEN';
    case CLOSED = 'CLOSED';
    case AUDITED = 'AUDITED';

    /**
     * Get label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Buka',
            self::CLOSED => 'Tutup',
            self::AUDITED => 'Teraudit',
        };
    }
}

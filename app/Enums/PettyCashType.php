<?php

namespace App\Enums;

enum PettyCashType: string
{
    case INVOICE = 'invoice';
    case REIMBURSE = 'reimburse';
    case PAGU = 'pagu';

    public function label(): string
    {
        return match ($this) {
            self::INVOICE => 'Invoice',
            self::REIMBURSE => 'Reimburse',
            self::PAGU => 'Pagu',
        };
    }
}

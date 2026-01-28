<?php

namespace App\Enums;

enum PettyCashType: string
{
    case INVOICE = 'invoice';
    case REIMBURSE = 'reimburse';
    case PENGOBATAN = 'pengobatan';

    public function label(): string
    {
        return match ($this) {
            self::INVOICE => 'Invoice',
            self::REIMBURSE => 'Reimburse',
            self::PENGOBATAN => 'Pengobatan',
        };
    }
}

<?php

namespace App\Enums;

enum PettyCashStatus: string
{
    case DRAFT = 'draft';
    case PENDING_MANAGER = 'pending_manager';
    case PENDING_DIRECTOR = 'pending_director';
    case PENDING_FINANCE = 'pending_finance';
    case PAID = 'paid';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_MANAGER => 'Menunggu Manager Approval',
            self::PENDING_DIRECTOR => 'Menunggu Director Approval',
            self::PENDING_FINANCE => 'Menunggu Finance Approval',
            self::PAID => 'Selesai / Dibayar',
            self::REJECTED => 'Ditolak',
        };
    }
}

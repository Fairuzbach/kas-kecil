<?php

namespace App\Enums;

enum PettyCashStatus: string
{
    case DRAFT = 'draft';
    case PENDING_SUPERVISOR = 'pending_supervisor';
    case PENDING_MANAGER = 'pending_manager';
    case PENDING_DIRECTOR = 'pending_director';
    case PENDING_FINANCE = 'pending_finance';
    case PENDING_KLINIK = 'pending_klinik';
    case PENDING_HC = 'pending_hc';
    case PAID = 'paid';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_SUPERVISOR => 'Menunggu Supervisor',
            self::PENDING_MANAGER => 'Menunggu Manager',
            self::PENDING_DIRECTOR => 'Menunggu Director',
            self::PENDING_HC => 'Menunggu Verifikasi Oleh Human Capital',
            self::PENDING_KLINIK => 'Menunggu Verifikasi Oleh Klinik',
            self::PENDING_FINANCE => 'Menunggu Finance Proses',
            self::PAID => 'Selesai / Dibayar',
            self::REJECTED => 'Ditolak',
        };
    }
}

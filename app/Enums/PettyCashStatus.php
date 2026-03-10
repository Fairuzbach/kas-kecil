<?php

namespace App\Enums;

enum PettyCashStatus: string
{
    case DRAFT = 'draft';
    case PENDING_SUPERVISOR = 'pending_supervisor';
    case PENDING_MANAGER = 'pending_manager';
    case PENDING_DIRECTOR = 'pending_director';
    case PENDING_FINANCE = 'pending_finance';
    case PENDING_FINANCE_MANAGER = 'pending_finance_manager';
    case READY_FOR_INFOR = 'ready_for_infor';
    case PENDING_KLINIK = 'pending_klinik';
    case PENDING_HC = 'pending_hc';
    case PAID = 'paid';
    case REJECTED = 'rejected';
    case REVISION = 'revision';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_SUPERVISOR => 'Menunggu Supervisor',
            self::PENDING_MANAGER => 'Menunggu Manager',
            self::PENDING_DIRECTOR => 'Menunggu Director',
            self::PENDING_HC => 'Menunggu Verifikasi Oleh Human Capital',
            self::PENDING_KLINIK => 'Menunggu Verifikasi Oleh Klinik',
            self::PENDING_FINANCE => 'Menunggu FA Verifikasi COA',
            self::PENDING_FINANCE_MANAGER => 'Menunggu FA MANAGER Prosess',
            self::READY_FOR_INFOR => 'Siap diupload ke INFOR',
            self::PAID => 'Selesai / Dibayar',
            self::REJECTED => 'Ditolak',
            self::REVISION => 'Dikembalikan (Butuh Revisi)',
        };
    }
}

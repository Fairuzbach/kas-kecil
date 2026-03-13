<?php

namespace App\Services;

use App\Models\PettyCashRequest;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappService;

class PettyCashWhatsappService
{
    /**
     * 1. NOTIFIKASI KE APPROVER SELANJUTNYA
     */
    public function notifyNextApprover(PettyCashRequest $request)
    {
        $status = $request->status->value;
        $approvers = collect();

        if ($status === 'pending_supervisor') {
            $approvers->push(User::find($request->approver_id));
        } elseif ($status === 'pending_manager') {
            if ($request->department && $request->department->manager_id) {
                $approvers->push(User::find($request->department->manager_id));
            }
        } elseif ($status === 'pending_director') {
            $approvers = User::where('role', 'director')
                ->where('director_group', $request->department->director_group ?? null)
                ->get();
        } elseif ($status === 'pending_finance') {
            $approvers = User::where('role', 'finance')->get()->filter(function ($u) {
                $emp = Employee::where('nik', $u->nik)->first();
                return $emp && strtolower($emp->level) !== 'manager';
            });
        } elseif ($status === 'pending_finance_manager') {
            $approvers = User::where('role', 'finance')->get()->filter(function ($u) {
                $emp = Employee::where('nik', $u->nik)->first();
                return $emp && strtolower($emp->level) === 'manager';
            });
        }

        $link = route('petty-cash.show', $request->id);

        foreach ($approvers as $approver) {
            $phone = $approver->phone_number ?? $approver->phone ?? null;
            if ($approver && !empty($phone)) {
                $msg = "*[FA PORTAL] PERMINTAAN APPROVAL PETTY CASH*\n\n" .
                    "YTH. *" . strtoupper($approver->name) . "*,\n\n" .
                    "TERDAPAT PENGAJUAN PETTY CASH YANG MEMERLUKAN PERSETUJUAN ANDA.\n\n" .
                    "*DETAIL PENGAJUAN*\n" .
                    "NO. REFERENSI : #{$request->tracking_number}\n" .
                    "PEMOHON       : " . strtoupper($request->user->name) . "\n" .
                    "DEPARTEMEN    : " . strtoupper($request->department->code) . "\n" .
                    "NOMINAL       : RP " . number_format($request->amount, 0, ',', '.') . "\n" .
                    "DIBAYAR KEPADA    : " . strtoupper($request->title) . "\n\n" .
                    "SILAKAN LAKUKAN REVIEW MELALUI TAUTAN BERIKUT:\n" .
                    "$link\n\n" .
                    "_PESAN INI DIKIRIM SECARA OTOMATIS OLEH SISTEM. HARAP TIDAK MEMBALAS PESAN INI._";

                $this->sendWa($phone, $msg);
            }
        }
    }

    /**
     * 2. NOTIFIKASI KE PEMOHON (Reject, Revisi, Infor, Paid)
     */
    public function notifyRequester(PettyCashRequest $request, string $action, string $note = '')
    {
        $requester = clone $request->user;
        $phone = $requester->phone_number ?? $requester->phone ?? null;

        if (!$requester || empty($phone)) return;

        $link = route('petty-cash.show', $request->id);
        $header = "*[FA PORTAL] NOTIFIKASI PETTY CASH*\n\n" .
            "YTH. *" . strtoupper($requester->name) . "*,\n\n";
        $footer = "\nUNTUK INFORMASI LEBIH LANJUT, SILAKAN AKSES TAUTAN BERIKUT:\n$link\n\n" .
            "_PESAN INI DIKIRIM SECARA OTOMATIS OLEH SISTEM. HARAP TIDAK MEMBALAS PESAN INI._";

        switch ($action) {
            case 'rejected':
                $msg = $header .
                    "KAMI INFORMASIKAN BAHWA PENGAJUAN PETTY CASH ANDA *DITOLAK*.\n\n" .
                    "*DETAIL PENGAJUAN*\n" .
                    "NO. REFERENSI : #{$request->tracking_number}\n\n" .
                    "*ALASAN PENOLAKAN*\n" .
                    strtoupper($note) .
                    $footer;
                break;

            case 'revision':
                $msg = $header .
                    "PENGAJUAN PETTY CASH ANDA MEMERLUKAN *REVISI* SEBELUM DAPAT DIPROSES LEBIH LANJUT.\n\n" .
                    "*DETAIL PENGAJUAN*\n" .
                    "NO. REFERENSI : #{$request->tracking_number}\n\n" .
                    "*CATATAN REVISI*\n" .
                    strtoupper($note) .
                    $footer;
                break;

            case 'ready_for_infor':
                $msg = $header .
                    "PENGAJUAN PETTY CASH ANDA TELAH *DISETUJUI* OLEH FINANCE MANAGER DAN SAAT INI SEDANG DALAM ANTRIAN UNTUK DIUNGGAH KE SISTEM INFOR.\n\n" .
                    "*DETAIL PENGAJUAN*\n" .
                    "NO. REFERENSI : #{$request->tracking_number}" .
                    $footer;
                break;

            case 'paid':
                $msg = $header .
                    "PENGAJUAN PETTY CASH ANDA TELAH *SELESAI DIPROSES* DAN DANA TELAH DICAIRKAN.\n\n" .
                    "*DETAIL PENGAJUAN*\n" .
                    "NO. REFERENSI : #{$request->tracking_number}\n" .
                    "NOMINAL       : RP " . number_format($request->amount, 0, ',', '.') .
                    $footer;
                break;

            default:
                return;
        }

        $this->sendWa($phone, $msg);
    }

    /**
     * Eksekusi Pengiriman WA
     */
    private function sendWa($phone, $message)
    {
        try {
            WhatsappService::send($phone, $message);
            Log::info("PettyCash WA Sukses terkirim ke: $phone");
        } catch (\Exception $e) {
            Log::error("Gagal kirim PettyCash WA ke $phone: " . $e->getMessage());
        }
    }
}

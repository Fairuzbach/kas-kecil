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
                $msg = "*[Finance Portal] Permintaan Approval Petty Cash*\n\n" .
                    "Yth. *{$approver->name}*,\n\n" .
                    "Terdapat pengajuan petty cash yang memerlukan persetujuan Anda.\n\n" .
                    "*Detail Pengajuan*\n" .
                    "No. Referensi : #{$request->tracking_number}\n" .
                    "Pemohon       : {$request->user->name}\n" .
                    "Departemen    : {$request->department->code}\n" .
                    "Nominal       : Rp " . number_format($request->amount, 0, ',', '.') . "\n" .
                    "Dibayar Kepada    : {$request->title}\n\n" .
                    "Silakan lakukan review melalui tautan berikut:\n" .
                    "$link\n\n" .
                    "_Pesan ini dikirim secara otomatis oleh sistem. Harap tidak membalas pesan ini._";

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
        $header = "*[Finance Portal] Notifikasi Petty Cash*\n\n" .
            "Yth. *{$requester->name}*,\n\n";
        $footer = "\nUntuk informasi lebih lanjut, silakan akses tautan berikut:\n$link\n\n" .
            "_Pesan ini dikirim secara otomatis oleh sistem. Harap tidak membalas pesan ini._";

        switch ($action) {
            case 'rejected':
                $msg = $header .
                    "Kami informasikan bahwa pengajuan petty cash Anda *ditolak*.\n\n" .
                    "*Detail Pengajuan*\n" .
                    "No. Referensi : #{$request->tracking_number}\n\n" .
                    "*Alasan Penolakan*\n" .
                    "{$note}" .
                    $footer;
                break;

            case 'revision':
                $msg = $header .
                    "Pengajuan petty cash Anda memerlukan *revisi* sebelum dapat diproses lebih lanjut.\n\n" .
                    "*Detail Pengajuan*\n" .
                    "No. Referensi : #{$request->tracking_number}\n\n" .
                    "*Catatan Revisi*\n" .
                    "{$note}" .
                    $footer;
                break;

            case 'ready_for_infor':
                $msg = $header .
                    "Pengajuan petty cash Anda telah *disetujui* oleh Finance Manager dan saat ini sedang dalam antrian untuk diunggah ke sistem INFOR.\n\n" .
                    "*Detail Pengajuan*\n" .
                    "No. Referensi : #{$request->tracking_number}" .
                    $footer;
                break;

            case 'paid':
                $msg = $header .
                    "Pengajuan petty cash Anda telah *selesai diproses* dan dana telah dicairkan.\n\n" .
                    "*Detail Pengajuan*\n" .
                    "No. Referensi : #{$request->tracking_number}\n" .
                    "Nominal       : Rp " . number_format($request->amount, 0, ',', '.') .
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

<?php

namespace App\Services;

use App\Models\PettyCashRequest;
use App\Enums\PettyCashStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalRequestMail;

class PettyCashService
{
    /**
     * Handle pembuatan request baru
     */
    public function createRequest(array $data, $user): PettyCashRequest
    {
        return DB::transaction(function () use ($data, $user) {
            $count = PettyCashRequest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count() + 1;

            $trackingNumber = 'PC-' . now()->format('Ym') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            $totalAmount = collect($data['items'])->sum('amount');
            $request = PettyCashRequest::create([
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'tracking_number' => $trackingNumber,
                'type' => $data['type'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'amount' => $totalAmount,
                'status' => PettyCashStatus::PENDING_MANAGER, // Default status
            ]);

            foreach ($data['items'] as $item) {
                $request->details()->create([
                    'item_name' => $item['item_name'],
                    'amount' => $item['amount'],
                    'coa_id' => $item['coa_id']
                ]);
            }
            // Kirim Email Notifikasi ke Manager Departemen
            // Logika email dipisah ke method private atau Event Listener
            // $this->notifyDepartmentManager($request);

            return $request;
        });
    }

    /**
     * Handle Approval Bertingkat
     */
    public function approveByManager(PettyCashRequest $request, $approver)
    {
        // Validasi logic (misal: approver harus manager dept yg sama)
        if ($approver->department_id !== $request->department_id) {
            throw new \Exception("Anda tidak memiliki akses approval departemen ini.");
        }

        $request->update([
            'status' => PettyCashStatus::PENDING_FINANCE, // Next Level
            'department_approved_at' => now(),
            'department_approver_id' => $approver->id,
        ]);

        // Kirim notifikasi ke Finance
    }
}

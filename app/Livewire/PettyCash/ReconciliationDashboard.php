<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use App\Models\PettyCashRequest;
use Livewire\WithPagination;
use Carbon\Carbon;

class ReconciliationDashboard extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;

    // Properti untuk Modal Preview
    public $previewUrl = null;
    public $showPreviewModal = false;

    public function mount()
    {
        // Default: Menarik data dari hari Senin sampai Minggu di minggu ini
        $this->startDate = Carbon::now()->timezone('Asia/Jakarta')->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->timezone('Asia/Jakarta')->endOfWeek()->format('Y-m-d');
    }

    public function openPreview($url)
    {
        $this->previewUrl = $url;
        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->previewUrl = null;
    }

    public function syncToOcr($requestId)
    {
        // Pastikan hanya FA yang bisa menjalankan aksi ini
        if (!in_array(auth()->user()->role, ['finance', 'cashier'])) {
            return;
        }

        $request = PettyCashRequest::with('details')->find($requestId);

        if ($request && $request->details->isNotEmpty()) {
            $firstDetail = $request->details->first();
            $ocrAmount = (float) $firstDetail->amount_ocr;

            if ($ocrAmount > 0) {
                // Samakan input user dengan hasil OCR
                $firstDetail->update(['amount' => $ocrAmount]);
                $request->update(['amount' => $ocrAmount]);

                session()->flash('success', "Nominal Ref #{$request->tracking_number} berhasil disinkronkan dengan hasil OCR (Rp " . number_format($ocrAmount, 0, ',', '.') . ").");
            }
        }
    }
    public function syncAllToOcr()
    {
        // 1. Ambil data pengajuan berdasarkan filter tanggal yang sedang aktif
        // Sesuaikan "YourModelName" dengan nama model aslimu (misal: WorkOrder, ExpenseRequest, dll)
        $requestsToSync = PettyCashRequest::with('details')
            ->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59'])
            ->get();

        $updatedCount = 0;

        // 2. Looping data untuk mencari yang selisih
        foreach ($requestsToSync as $req) {
            $firstItem = $req->details->first();
            $ocrTotal = (float) ($firstItem->amount_ocr ?? 0);
            $currentTotal = (float) $req->amount;

            $isMatched = $ocrTotal > 0 && abs($ocrTotal - $currentTotal) < 0.01;
            $hasNoOcr = $ocrTotal <= 0;

            // Jika ADA hasil OCR dan nominalnya TIDAK SAMA (selisih)
            if (!$isMatched && !$hasNoOcr) {
                // Update nominal input agar sesuai dengan OCR
                $req->update([
                    'amount' => $ocrTotal
                ]);
                $updatedCount++;
            }
        }

        // 3. Beri notifikasi ke user
        if ($updatedCount > 0) {
            session()->flash('success', "Berhasil menyinkronkan $updatedCount data dengan hasil OCR!");
        } else {
            session()->flash('success', "Tidak ada data yang perlu disinkronkan.");
        }
    }

    public function render()
    {
        // Ambil data dalam rentang tanggal, abaikan yang masih draft
        $requests = PettyCashRequest::with(['user', 'details', 'department'])
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->whereNotIn('status', ['draft'])
            ->orderBy('created_at', 'desc')
            ->get(); // Kita pakai get() dulu agar mudah direkap di Blade

        return view('livewire.petty-cash.reconciliation-dashboard', [
            'requests' => $requests
        ])->layout('layouts.app');
    }
}

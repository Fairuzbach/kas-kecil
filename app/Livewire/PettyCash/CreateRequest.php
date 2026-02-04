<?php

namespace App\Livewire\PettyCash;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload file
use Livewire\Attributes\Validate;
use App\Services\PettyCashService;
use App\Models\Coa;
use App\Enums\PettyCashType;
use App\Models\PettyCashDetail;

class CreateRequest extends Component
{
    use WithFileUploads;

    public $title = '';
    public $type  = '';
    public $description = '';
    public $attachment;
    public $user_department = '';

    public function mount()
    {
        $user = auth()->user();
        if ($user->department) {
            $this->user_department = $user->department->code . ' - ' . $user->department->name;
        } else {
            $this->user_department = 'No Department';
        }
    }

    // ARRAY DINAMIS (Default 1 baris kosong)
    public $items = [
        ['item_name' => '', 'amount' => '', 'coa_id' => '']
    ];

    // Tambah Baris Baru
    public function addItem()
    {
        $this->items[] = ['item_name' => '', 'amount' => '', 'coa_id' => '', 'type' => ''];
    }

    // Hapus Baris
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Re-index array
    }

    // Hitung Total Real-time (Opsional, untuk tampilan saja)
    public function getTotalProperty()
    {
        return collect($this->items)->sum(fn($i) => (int) ($i['amount'] ?? 0));
    }

    public function save($status = 'pending_manager')
    {
        // 1. BERSIHKAN BARIS KOSONG
        $this->items = collect($this->items)->filter(function ($item) {
            return trim($item['item_name']) !== '';
        })->values()->all();

        // 2. CEK JIKA KOSONG SETELAH DIBERSIHKAN
        if (empty($this->items)) {
            $this->addError('items', 'Minimal harus ada 1 baris item.');
            return;
        }

        // 3. VALIDASI (Hapus items.*.type karena sudah pindah ke header)
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.amount' => 'required|numeric|min:1000',
            'items.*.coa_id' => 'required|exists:coas,id',
            // 'items.*.type' => 'required',  <-- HAPUS INI (Sudah tidak ada di form detail)
            'attachment' => 'nullable|image|max:2048',
        ]);

        // 4. TENTUKAN STATUS ENUM
        $statusEnum = match ($status) {
            'draft' => \App\Enums\PettyCashStatus::DRAFT,
            default => \App\Enums\PettyCashStatus::PENDING_MANAGER
        };

        $attachmentPath = $this->attachment ? $this->attachment->store('attachments', 'public') : null;

        // 5. PANGGIL SERVICE (Gunakan helper app() agar tidak error undefined variable)
        app(\App\Services\PettyCashService::class)->createRequest([
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'attachment' => $attachmentPath,
            'items' => $this->items,
            'status' => $statusEnum, // <--- JANGAN LUPA KIRIM STATUS INI
        ], auth()->user());

        // 6. RESET FORM
        $this->reset(['title', 'type', 'description', 'attachment']);

        // Perbaikan Reset Array (Harus Array di dalam Array)
        $this->items = [['item_name' => '', 'amount' => '', 'coa_id' => '']];

        // 7. FEEDBACK & TUTUP MODAL (Jangan Redirect agar smooth)
        $msg = $status === 'draft' ? 'Disimpan sebagai Draft.' : 'Pengajuan dikirim ke Manager!';
        session()->flash('success', $msg);

        // Kirim sinyal untuk tutup modal & refresh tabel
        $this->dispatch('request-created');
    }

    public function details()
    {
        return $this->hasMany(PettyCashDetail::class);
    }

    public function render()
    {
        $user = auth()->user();

        $filteredCoas = \App\Models\Coa::query()
            // 1. Ambil yang KHUSUS milik departemen user (misal: IT)
            ->whereHas('departments', function ($query) use ($user) {
                $query->where('departments.id', $user->department_id);
            })
            // 2. ATAU ambil yang GLOBAL (yang kolom divisinya "ALL DEPARTMENT" tadi)
            // orDoesntHave = Cari yang tidak punya relasi di tabel pivot
            ->orDoesntHave('departments')

            ->orderBy('code')
            ->get();

        return view('livewire.petty-cash.create-request', [
            'coas' => $filteredCoas,
            'types' => \App\Enums\PettyCashType::cases(),
        ]);
    }
}

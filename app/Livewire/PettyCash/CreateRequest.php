<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PettyCashDetail;
use Illuminate\Support\Number;

class CreateRequest extends Component
{
    use WithFileUploads;

    public $title = '';
    public $type  = '';
    public $description = '';
    public $user_department = '';
    public $attachment;
    public $attachment_receipt;
    public $attachment_prescription;
    public $search_keyword = '';
    public $employee_result = [];
    public $is_searching = false;
    public $division_employees = [];
    public $selected_employee_id;
    public $selected_approver_id;
    public $supervisors = [];
    public $terbilang = '';
    public $total = 0;
    public $tanggal;
    public $tracking_number;
    public $dibayar_kepada;




    public function mount()
    {

        $user = auth()->user();
        $this->user_department = $user->department ? ($user->department->code . ' - ' . $user->department->name) : 'No Department';
        $this->supervisors = \App\Models\User::where('role', 'supervisor')
            ->where('division_id', $user->division_id)
            ->get();
    }


    public $items = [
        ['item_name' => '', 'amount' => '', 'coa_id' => '']
    ];

    public function updated($property, $value = null)
    {
        if (str_starts_with($property, 'items.')) {
            $this->calculateTotal();

            if (preg_match('/items\.(\d+).item_name/', $property, $matches)) {
                $index = $matches[1];

                $this->autoSuggestByKeyword($index, $value);
            }

            if (preg_match('/items\.(\d+)\.coa_id/', $property, $matches)) {
                $index = $matches[1];
                $this->suggestedCoas[$index] = null;
            }
        }
    }

    public function ignoreSuggestion($index)
    {
        $this->suggestedCoas[$index] = null;
    }

    public $suggestedCoas = [];
    public $inlineSuggestions = [];

    private function autoSuggestByKeyword($index, $text)
    {
        $this->suggestedCoas[$index] = null;
        $this->inlineSuggestions[$index] = null;

        if (empty($text)) return;

        $textLower = strtolower($text);
        $user = auth()->user();

        $words = explode(' ', $text);
        $lastWordOriginal = end($words);
        $lastWordLower = strtolower($lastWordOriginal);


        $coas = \App\Models\Coa::query()->whereNotNull('keywords')->where(function ($query) use ($user) {
            $query->whereHas('departments', function ($q) use ($user) {
                $q->where('departments.id', $user->department_id);
            })->orDoesntHave('departments');
        })->get();

        $foundCoa = false;
        $foundInline = false;

        foreach ($coas as $coa) {
            $keywords = explode(',', strtolower($coa->keywords));

            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);

                if (!$foundCoa && strlen($textLower) >= 3 && str_contains($textLower, $keyword)) {
                    $this->suggestedCoas[$index] = [
                        'id' => $coa->id,
                        'label' => $coa->code . ' - ' . $coa->name
                    ];
                    $foundCoa = true;
                }

                if (!$foundInline && strlen($lastWordLower) >= 2) {
                    if (str_starts_with($keyword, $lastWordLower) && $keyword !== $lastWordLower) {
                        $sisaHuruf = substr($keyword, strlen($lastWordLower));
                        $this->inlineSuggestions[$index] = $text . $sisaHuruf;
                        $foundInline = true;
                    }
                }

                if ($foundCoa && $foundInline) {
                    break 2;
                }
            }
        }
    }

    public function acceptInlineSuggestion($index)
    {
        if (!empty($this->inlineSuggestions[$index])) {
            $this->items[$index]['item_name'] = $this->inlineSuggestions[$index];
            $this->inlineSuggestions[$index] = null;

            $this->autoSuggestByKeyword($index, $this->items[$index]['item_name']);
        }
    }

    public function applySuggestion($index, $coaId)
    {
        $this->items[$index]['coa_id'] = $coaId;
        $this->suggestedCoas[$index] = null;
    }

    public function calculateTotal()
    {
        $amount = 0;
        foreach ($this->items as $item) {
            $amount += (float) ($item['amount'] ?? 0);
        }
        $this->total = $amount;

        if ($this->total == 0) {
            $this->terbilang = 'Nol Rupiah';
        } else {
            $this->terbilang = $this->convertTerbilang(abs($this->total)) . ' Rupiah';
        }
    }

    public function addTax($index, $type)
    {
        $baseAmount = (int) ($this->items[$index]['amount'] ?? 0);

        if ($baseAmount <= 0) return;

        $taxAmount = 0;
        $itemName = '';
        $coaId = '';

        if ($type === 'ppn') {
            $taxAmount = $baseAmount * 0.11;
            $itemName = 'PPN 11% (Ref: ' . $this->items[$index]['item_name'] . ')';
            $coaId = 'tax_ppn';
        } elseif ($type === 'pph23') {
            // Hasilnya akan minus, misal: -2000
            $taxAmount = round($baseAmount * 0.02) * -1;
            $itemName = 'Potongan PPh 23 2% (Ref: ' . $this->items[$index]['item_name'] . ')';
            $coaId = 'tax_pph';
        }
        $this->items[] = [
            'item_name' => $itemName,
            'coa_id' => $coaId,
            'amount' => $taxAmount
        ];

        $this->calculateTotal();
    }
    private function convertTerbilang($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->convertTerbilang($nilai - 10) . " Belas ";
        } else if ($nilai < 100) {
            $temp = $this->convertTerbilang($nilai / 10) . " Puluh " . $this->convertTerbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus " . $this->convertTerbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->convertTerbilang($nilai / 100) . " Ratus " . $this->convertTerbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu " . $this->convertTerbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->convertTerbilang($nilai / 1000) . " Ribu " . $this->convertTerbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->convertTerbilang($nilai / 1000000) . " Juta " . $this->convertTerbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->convertTerbilang($nilai / 1000000000) . " Milyar " . $this->convertTerbilang(fmod($nilai, 1000000000));
        }
        return trim($temp);
    }


    public function addItem()
    {
        $this->items[] = ['item_name' => '', 'amount' => '', 'coa_id' => '', 'type' => ''];
    }


    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function getTotalProperty()
    {
        return collect($this->items)->sum(fn($i) => (int) ($i['amount'] ?? 0));
    }

    public function save($status = 'pending_manager')
    {
        // 1. Filter baris kosong dulu agar tidak divalidasi
        $this->items = collect($this->items)->filter(function ($item) {
            return trim($item['item_name']) !== '';
        })->values()->all();

        if (empty($this->items)) {
            $this->addError('items', 'Minimal harus ada 1 baris item.');
            return;
        }
        $this->title = $this->dibayar_kepada;
        // 2. Definisi Rules
        $rules = [
            'title'             => 'required|string|max:255',
            'type'              => 'required',
            'items'             => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $coaId = $this->items[$index]['coa_id'] ?? null;
                    $numericValue = (float) $value;

                    if ($coaId === 'tax_pph') {
                        // KHUSUS PPh 23: Harus minus dan tidak boleh 0
                        if ($numericValue >= 0) {
                            $fail('Nominal PPh 23 harus berupa potongan (angka minus).');
                        }
                    } elseif ($coaId === 'tax_ppn') {
                        // KHUSUS PPN: Harus plus dan tidak boleh 0
                        if ($numericValue <= 0) {
                            $fail('Nominal PPN harus lebih dari 0.');
                        }
                    } else {
                        // BARIS BIASA: Minimal 1.000
                        if ($numericValue < 1000) {
                            $fail('Minimal pengajuan item adalah Rp 1.000.');
                        }
                    }
                },
            ],
            'items.*.coa_id'    => [
                'required',
                function ($attribute, $value, $fail) {
                    if (in_array($value, ['tax_ppn', 'tax_pph'])) return;
                    if (!\App\Models\Coa::where('id', $value)->exists()) {
                        $fail('The selected COA is invalid.');
                    }
                },
            ],
            'attachment'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if (count($this->supervisors ?? []) > 0 && $status !== 'draft') {
            $rules['selected_approver_id'] = 'required';
        }

        // 3. Jalankan Validasi
        $this->validate($rules);

        // 4. Cek Tipe Pengobatan
        if ($this->type === 'pengobatan') {
            $this->dispatch('swal', [
                'title' => 'Fitur Dinonaktifkan',
                'text'  => 'Mohon maaf, pengajuan tipe pengobatan sedang dinonaktifkan sementara.',
                'icon'  => 'warning'
            ]);
            return;
        }

        // 5. Mapping ID Pajak (Lakukan SETELAH validasi agar data DB aman)
        $ppnCoa = \App\Models\Coa::where('code', '2101')->first();
        $pphCoa = \App\Models\Coa::where('code', '2102')->first();

        $cleanedItems = collect($this->items)->map(function ($item) use ($ppnCoa, $pphCoa) {
            $finalCoaId = $item['coa_id'];
            if ($finalCoaId === 'tax_ppn') $finalCoaId = $ppnCoa->id ?? null;
            if ($finalCoaId === 'tax_pph') $finalCoaId = $pphCoa->id ?? null;

            return [
                'item_name' => $item['item_name'],
                'amount'    => $item['amount'],
                'coa_id'    => $finalCoaId,
            ];
        })->toArray();

        // 6. Handle File
        $mainFile = $this->attachment ? $this->attachment->store('attachments', 'public') : null;

        // 7. Penentuan Status
        $statusEnum = \App\Enums\PettyCashStatus::PENDING_MANAGER;
        if ($status === 'draft') {
            $statusEnum = \App\Enums\PettyCashStatus::DRAFT;
        } else {
            $statusEnum = !empty($this->selected_approver_id)
                ? \App\Enums\PettyCashStatus::PENDING_SUPERVISOR
                : \App\Enums\PettyCashStatus::PENDING_MANAGER;
        }

        // 8. Simpan via Service
        app(\App\Services\PettyCashService::class)->createRequest([
            'title'            => $this->title,
            'type'             => $this->type,
            'description'      => $this->description,
            'attachment'       => $mainFile,
            'items'            => $cleanedItems,
            'approver_id'      => $this->selected_approver_id,
            'status'           => $statusEnum,
        ], auth()->user());

        // 9. Reset & Redirect
        session()->flash('success', ($status === 'draft') ? 'Disimpan sebagai Draft.' : 'Pengajuan berhasil dibuat!');
        return redirect()->route('dashboard');
    }
    public function details()
    {
        return $this->hasMany(PettyCashDetail::class);
    }

    public function render()
    {
        $user = auth()->user();
        $this->calculateTotal();
        $filteredCoas = \App\Models\Coa::query()
            ->whereHas('departments', function ($query) use ($user) {
                $query->where('departments.id', $user->department_id);
            })
            ->orDoesntHave('departments')
            ->orderBy('code')
            ->get();

        return view('livewire.petty-cash.create-request', [
            'coas' => $filteredCoas,
            'types' => \App\Enums\PettyCashType::cases(),
        ]);
    }
    public function updatedSearchKeyword()
    {
        if (strlen($this->search_keyword) < 2) {
            $this->employee_result = [];
            return;
        }

        $keyword = trim($this->search_keyword);
        $user = auth()->user();
        if (!$user->division_id || !$user->branch) {
            $this->employee_result = [];
            return;
        }

        $query = \App\Models\Employee::query()
            ->with(['department', 'division'])
            ->where('division_id', $user->division_id)
            ->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('nik', 'LIKE', '%' . $keyword . '%');
            })
            ->limit(10)
            ->get();

        $this->employee_result = $query->map(function ($employee) {
            return [
                'id'              => $employee->id,
                'nik'             => $employee->nik,
                'name'            => $employee->name,
                'department_name' => $employee->department->name ?? '-',
                'division_name'   => $employee->division->name ?? '-',
                'branch'          => $employee->branch // Opsional: Tampilkan branch di list
            ];
        })->toArray();
    }

    public function updatedSelectedEmployeeId($nik)
    {

        $emp = collect($this->division_employees)->where('nik', $nik)->first();

        if ($emp) {
            $this->title = "{$emp['name']} ({$emp['nik']}) - {$emp['divisi']}";
        }
    }

    public function selectEmployee($name, $nik, $divisi)
    {

        $this->title = "{$name} ({$nik}) - {$divisi}";
        $this->selected_nik = $nik;
        $this->search_keyword = '';
        $this->employee_result = [];
    }
}

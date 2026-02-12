<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PettyCashDetail;

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
        $user = auth()->user();


        $this->items = collect($this->items)->filter(function ($item) {
            return trim($item['item_name']) !== '';
        })->values()->all();


        if (empty($this->items)) {
            $this->addError('items', 'Minimal harus ada 1 baris item.');
            return;
        }
        $coaRule = ($this->type === 'pengobatan') ? 'nullable' : 'required|exists:coas,id';

        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.amount' => 'required|numeric|min:1000',
            'items.*.coa_id' => $coaRule,
        ];

        if ($this->type === 'pengobatan') {
            $rules['attachment_receipt'] = 'required|image|max:2048';
            $rules['attachment_prescription'] = 'required|image|max:2048';
        } else {
            $rules['attachment'] = 'nullable|image|max:2048';
        }

        $this->validate($rules);


        $mainFile = null;
        $extraFile = null;

        if ($this->type === 'pengobatan') {
            if ($this->attachment_receipt) {
                $mainFile = $this->attachment_receipt->store('attachments', 'public');
            }
            if ($this->attachment_prescription) {
                $extraFile = $this->attachment_prescription->store('attachments', 'public');
            }
        } else {
            if ($this->attachment) {
                $mainFile = $this->attachment->store('attachments', 'public');
            }
        }

        $hasSupervisors = count($this->supervisors) > 0;

        if ($this->type !== 'pengobatan' && $hasSupervisors && empty($this->selected_approver_id)) {
            $this->addError('selected_approver_id', 'Anda wajib memilih Supervisor untuk Approval.');
        }

        if ($status === 'draft') {
            $statusEnum = \App\Enums\PettyCashStatus::DRAFT;
        } else {
            if ($this->type === 'pengobatan') {
                $statusEnum = \App\Enums\PettyCashStatus::PENDING_MANAGER;
            } else {
                if ($user->selected_approver_id) {
                    $statusEnum = \App\Enums\PettyCashStatus::PENDING_SUPERVISOR;
                } else {
                    $statusEnum = \App\Enums\PettyCashStatus::PENDING_MANAGER;
                }
            }
        }
        $cleanedItems = collect($this->items)->map(function ($item) {

            $coaValue = $item['coa_id'];
            return [
                'item_name' => $item['item_name'],
                'amount'    => $item['amount'],
                'coa_id'    => empty($coaValue) ? null : $coaValue,
            ];
        })->toArray();
        app(\App\Services\PettyCashService::class)->createRequest([
            'title'            => $this->title,
            'type'             => $this->type,
            'description'      => $this->description,
            'attachment'       => $mainFile,
            'extra_attachment' => $extraFile,
            'items'            => $cleanedItems,
            'approver_id' => $this->selected_approver_id,
            'status'           => $statusEnum,

        ], auth()->user());

        $this->reset([
            'title',
            'type',
            'description',
            'attachment',
            'attachment_receipt',
            'attachment_prescription',
            'search_keyword',
            'employee_result'
        ]);

        $this->items = [['item_name' => '', 'amount' => '', 'coa_id' => '']];

        if ($status === 'draft') {
            $msg = 'Disimpan sebagai Draft.';
        } elseif ($statusEnum === \App\Enums\PettyCashStatus::PENDING_SUPERVISOR) {
            $msg = 'Pengajuan kesehatan dikirim ke Supervisor';
        } else {
            $msg = 'Pengajuan dikirim ke Manager';
        }

        session()->flash('success', $msg);
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

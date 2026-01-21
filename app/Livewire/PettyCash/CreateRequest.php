<?php

namespace App\Livewire\PettyCash;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload file
use Livewire\Attributes\Validate;
use App\Services\PettyCashService;
use App\Models\Coa;
use App\Enums\PettyCashType;

class CreateRequest extends Component
{
    use WithFileUploads;

    // Property Form
    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('required')] // Validasi Enum akan kita handle manual/otomatis
    public $type = '';

    #[Validate('required|numeric|min:1000')]
    public $amount = '';

    #[Validate('required|exists:coas,id')]
    public $coa_id = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('nullable|image|max:2048')] // Maks 2MB
    public $attachment;

    // Method Save (Clean & Slim)
    public function save(PettyCashService $service)
    {
        $this->validate();

        try {
            // Upload file jika ada
            $attachmentPath = null;
            if ($this->attachment) {
                $attachmentPath = $this->attachment->store('attachments', 'public');
            }

            // Panggil Service (Business Logic ada di sana)
            $service->createRequest([
                'title' => $this->title,
                'type' => $this->type, // Invoice/Reimburse/Pagu
                'amount' => $this->amount,
                'coa_id' => $this->coa_id,
                'description' => $this->description,
                'attachment' => $attachmentPath,
            ], auth()->user());

            // Flash Message & Redirect
            session()->flash('success', 'Pengajuan berhasil dibuat! Menunggu approval.');
            return $this->redirect('/dashboard', navigate: true);
        } catch (\Exception $e) {
            $this->addError('general', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Cek apakah user punya departemen?
        // Jika punya, ambil COA departemen tersebut. Jika tidak, kosongkan array.
        $coas = $user->department ? $user->department->coas : [];

        return view('livewire.petty-cash.create-request', [
            'types' => PettyCashType::cases(),
            'coas' => $coas,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PettyCashStatus;
use App\Enums\PettyCashType;

class PettyCashRequest extends Model
{
    protected $guarded = ['id'];

    // Casting otomatis Enum & Tanggal
    protected $casts = [
        'status' => PettyCashStatus::class,
        'type' => PettyCashType::class,
        'amount' => 'decimal:2',
        'manager_approved_at' => 'datetime',
        'director_approved_at' => 'datetime',
        'finance_approved_at' => 'datetime',
    ];

    // Relasi
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function coa(): BelongsTo
    {
        return $this->belongsTo(Coa::class);
    }
}

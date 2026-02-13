<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PettyCashStatus;
use App\Enums\PettyCashType;

class PettyCashRequest extends Model
{
    protected $fillable = [
        'tracking_number',
        'user_id',
        'approver_id',
        'department_id',
        'title',
        'description',
        'amount',
        'type',
        'status',
        'attachment',
        'extra_attachment',
        'supervisor_approved_at',
        'manager_approved_at',
        'manager_approver_id',
        'finance_approved_at',
        'finance_approver_id',
        'director_approved_at',
        'director_approver_id',
        'hc_approved_at',
        'klinik_approved_at',
        'rejected_at',
        'rejected_reason',
    ];

    protected $casts = [
        'status' => PettyCashStatus::class,
        'type'   => PettyCashType::class,
        'amount' => 'decimal:2',
        'supervisor_approved_at' => 'datetime',
        'manager_approved_at'    => 'datetime',
        'director_approved_at'   => 'datetime',
        'finance_approved_at'    => 'datetime',
        'hc_approved_at' => 'datetime',
        'klinik_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->tracking_number)) {

                $model->tracking_number = 'PC-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            }
        });
    }


    public function details()
    {
        return $this->hasMany(PettyCashDetail::class);
    }

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
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

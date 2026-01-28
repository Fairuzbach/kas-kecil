<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PettyCashType;

class PettyCashDetail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function request()
    {
        return $this->belongsTo(PettyCashRequest::class, 'petty_cash_request_id');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }
}

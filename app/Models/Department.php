<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['name', 'manager_id', 'director_group'];
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi Many-to-Many ke COA
    public function coas()
    {
        return $this->belongsToMany(Coa::class, 'coa_department');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function divisions()
    {
        return $this->hasMany(Division::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}

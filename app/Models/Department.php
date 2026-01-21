<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi Many-to-Many ke COA
    public function coas()
    {
        return $this->belongsToMany(Coa::class, 'coa_department');
    }
}

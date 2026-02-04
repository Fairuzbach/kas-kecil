<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $guarded = ['id'];

    public function departments()
    {
        // Relasi Many-to-Many
        return $this->belongsToMany(Department::class, 'coa_department');
    }
}

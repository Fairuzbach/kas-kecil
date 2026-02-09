<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nik',
        'name',
        'job_title',
        'level',
        'organization',
        'branch',
        'status',
        'department_id',
        'division_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }
}

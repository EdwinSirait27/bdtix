<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeDepartment extends Pivot
{
 protected $connection = 'hrx'; 
    protected $table = 'employee_departments';
    protected $primaryKey = 'id';
    public $incrementing = false; 
    protected $keyType = 'string';
    protected $fillable = [
        'employee_id',
        'department_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}

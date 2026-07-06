<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeePosition extends Pivot
{
    
  protected $connection = 'hrx'; 
    protected $table = 'employee_positions';
    protected $primaryKey = 'id';
    public $incrementing = false; 
    protected $keyType = 'string';
    protected $fillable = [
        'employee_id',
        'position_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}

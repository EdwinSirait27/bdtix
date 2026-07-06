<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Ramsey\Uuid\Uuid;

class EmployeeStore extends Pivot
{
    protected $connection = 'hrx'; 
    protected $table = 'employee_stores';
    protected $primaryKey = 'id';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'employee_id',
        'store_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
// <!-- namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

// class EmployeeStore extends Model
// {
//     protected $connection = 'hrx'; 
//     protected $table = 'employee_stores';
//     protected $primaryKey = 'id';
//     public $incrementing = false; 
//     protected $keyType = 'string';
//     protected $fillable = [
//         'employee_id',
//         'store_id',
//         'is_primary',
//     ];

//     protected $casts = [
//         'is_primary' => 'boolean',
//     ];

//     public function employee()
//     {
//         return $this->belongsTo(Employee::class, 'employee_id', 'id');
//     }

//     public function store()
//     {
//         return $this->belongsTo(Store::class, 'store_id', 'id');
//     }
// } -->


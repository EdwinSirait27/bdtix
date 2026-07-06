<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// class Employee extends Model
// {
//      protected $connection = 'hrx'; // sesuai koneksi HRIS kamu
//     protected $table = 'employees_tables';
//     protected $primaryKey = 'id';
//     public $incrementing = false; // kalau kamu pakai UUID
//     protected $keyType = 'string';

//     protected $fillable = [
//         'employee_name',
//         'employee_pengenal',
//         'company_id',
//         'store_id',
//         'department_id',
//         'position_id',
//     ];

//     // relasi ke Department
//     public function department()
//     {
//         return $this->belongsTo(Department::class, 'department_id');
//     }

//     // relasi ke Position
//     public function position()
//     {
//         return $this->belongsTo(Position::class, 'position_id');
//     }
//     public function company()
//     {
//         return $this->belongsTo(Company::class, 'company_id');
//     }
//     public function store()
//     {
//         return $this->belongsTo(Store::class, 'store_id');
//     }

//     // relasi ke User (HRIS)
//     public function user()
//     {
//         return $this->hasOne(User::class, 'employee_id');
//     }
 

// }
class Employee extends Model
{
     protected $connection = 'hrx'; // sesuai koneksi HRIS kamu
    protected $table = 'employees_tables';
    protected $primaryKey = 'id';
    public $incrementing = false; // kalau kamu pakai UUID
    protected $keyType = 'string';

    protected $fillable = [
        'employee_name',
        'employee_pengenal',
        'company_id',
    ];

    public function store()
    {
        return $this->belongsToMany(
            Store::class,
            'employee_stores',
            'employee_id',
            'store_id'
        )
            ->withPivot('is_primary')
            ->withTimestamps()
            ->using(EmployeeStore::class);
    }
    public function department()
    {
        return $this->belongsToMany(
            Department::class,
            'employee_departments',
            'employee_id',
            'department_id'
        )
            ->withPivot('is_primary')
            ->withTimestamps()
            ->using(EmployeeDepartment::class);
    }
    public function position()
    {
        return $this->belongsToMany(
            Position::class,
            'employee_positions',
            'employee_id',
            'position_id'
        )
            ->withPivot('is_primary')
            ->withTimestamps()
            ->using(EmployeePosition::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // relasi ke User (HRIS)
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
 

}

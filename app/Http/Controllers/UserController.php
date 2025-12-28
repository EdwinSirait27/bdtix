<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class UserController extends Controller
{
    public function users()
    {
        return view('pages.users');
    }

    public function getUsers(Request $request)
    {
        $query = User::select([
            'users.id',
            'users.username',
            'users.employee_id',
            'employees_tables.status',
            'employees_tables.employee_name',
            'company_tables.name as company_name',
            'departments_tables.department_name',
            'stores_tables.name as store_name',
            'position_tables.name as position_name',
        ])
            ->leftJoin('employees_tables', 'employees_tables.id', '=', 'users.employee_id')
            ->leftJoin('company_tables', 'company_tables.id', '=', 'employees_tables.company_id')
            ->leftJoin('departments_tables', 'departments_tables.id', '=', 'employees_tables.department_id')
            ->leftJoin('stores_tables', 'stores_tables.id', '=', 'employees_tables.store_id')
            ->leftJoin('position_tables', 'position_tables.id', '=', 'employees_tables.position_id')
            ->whereIn('employees_tables.status', ['Active', 'Pending', 'Mutation']);

        return DataTables::eloquent($query)
            ->addColumn('roles', function ($user) {
                $roles = $user->getRoleNames(); // dari Spatie

                if ($roles->isEmpty()) {
                    return '<span class="badge bg-secondary">No Role</span>';
                }

                return $roles->map(
                    fn($role) =>
                    '<span class="badge bg-primary me-1">' . $role . '</span>'
                )->implode('');
            })
    //         ->addColumn('action', function ($user) {
    //             $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
    //             return '
    //     <a href="' . route('editusers', $idHashed) . '"
    //        class="btn btn-sm btn-outline-secondary"
    //        title="Edit Roles: ' . e($user->employee->employee_name) . '">
    //        <i class="fas fa-edit"></i> Edit
    //     </a>
    // ';
    //         })
    ->addColumn('action', function ($user) {
    $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);

    return '
        <a href="' . route('editusers', $idHashed) . '"
           class="inline-flex items-center justify-center p-2 
                  text-slate-500 hover:text-indigo-600 
                  hover:bg-indigo-50 rounded-full transition"
           title="Edit Roles: ' . e($user->employee->employee_name) . '">

            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor" 
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 3.487a2.1 2.1 0 013.001 2.949L7.125 19.174 
                         3 21l1.826-4.125L16.862 3.487z" />
            </svg>

        </a>
    ';
})

            ->rawColumns(['roles', 'action'])
            ->make(true);
    }
    //  public function edit($hashedId)
    // {
    //     $user = User::get()->first(function ($u) use ($hashedId) {
    //         $expectedHash = substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8);
    //         return $expectedHash === $hashedId;
    //     });
    //     if (!$user) {
    //         abort(404, 'User not found.');
    //     }
    //     $userStatus = ['Active', 'Inactive'];
    //     $selectedStatus = old('status', $user->Employee->status ?? '');
    //     $selectedRole = old('role', optional($user->roles->first())->name ?? '');
    //     return view('pages.editusers', [
    //         'user' => $user,
    //         'hashedId' => $hashedId,
    //         'userStatus' => $userStatus,
    //         'selectedStatus' => $selectedStatus,
    //         'selectedRole' => $selectedRole
    //     ]);
    // }
    public function edit($hash)
    {
        $user = User::all()->first(function ($u) use ($hash) {
            return substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8) === $hash;
        });
        abort_if(!$user, 404);
        $roles = Role::where('guard_name', 'web')->get();
        $userRoles = $user->getRoleNames()->toArray();
        return view('pages.editusers', compact('user', 'roles', 'userRoles'));
    }
    public function update(Request $request, $hash)
{
    $user = User::all()->first(function ($u) use ($hash) {
        return substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8) === $hash;
    });
    abort_if(!$user, 404);
    $request->validate([
        'roles' => 'nullable|array',
        'roles.*' => 'exists:roles,name',
    ]);
    // Sync role (hapus lama + insert baru)
    $user->syncRoles($request->roles ?? []);
    return redirect()
        ->route('users')
        ->with('success', 'Role Updated Successfully');
}
    // public function getUsers(Request $request)
    // {
    //     $query = User::select([
    //         'users.id',
    //         'users.username',
    //         'users.employee_id',
    //         'employees_tables.status',
    //         'employees_tables.employee_name',
    //         'employees_tables.employee_name as employee_name',
    //         'company_tables.name as company_name',
    //         'departments_tables.department_name',
    //         'stores_tables.name as store_name',
    //         'position_tables.name as position_name',
    //     ])
    //         ->leftJoin('employees_tables', 'employees_tables.id', '=', 'users.employee_id')
    //         ->leftJoin('company_tables', 'company_tables.id', '=', 'employees_tables.company_id')
    //         ->leftJoin('departments_tables', 'departments_tables.id', '=', 'employees_tables.department_id')
    //         ->leftJoin('stores_tables', 'stores_tables.id', '=', 'employees_tables.store_id')
    //         ->leftJoin('position_tables', 'position_tables.id', '=', 'employees_tables.position_id')
    //         ->whereIn('employees_tables.status', ['Active', 'Pending', 'Mutation']);
    //     return DataTables::eloquent($query)
    //         ->addColumn('action', function ($user) {
    //             $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
    //             return '
    //     <a href="' . route('editusers', $idHashed) . '"
    //        class="btn btn-sm btn-outline-secondary"
    //        title="Edit Roles: ' . e($user->employee->employee_name) . '">
    //        <i class="fas fa-edit"></i> Edit
    //     </a>
    // ';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }
}
// public function getUsers(Request $request)
    // {
    //     $users = User::select(['id', 'username', 'employee_id'])->with('employee')
    //     ->employeeActiveOrPending()
    //         ->get()
    //         ->map(function ($user) {
    //             $user->id_hashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
    //             $user->action = '
    //             <a href="' . route('editusers', $user->id_hashed) . '" class="mx-3" data-bs-toggle="tooltip" title="Edit User: ' . e($user->username) . '">
    //                 <i class="fas fa-user-edit text-secondary"></i>
    //             </a>';
    //             return $user;
    //         });
    //     return DataTables::of($users)
    //      ->addColumn('employee_name', fn($user) => optional($user->employee)->employee_name ?? 'Empty')
    //     ->addColumn('company_name', fn($user) => optional(optional($user->employee)->company)->name ?? 'Empty')
    //     ->addColumn('department_name', fn($user) => optional(optional($user->employee)->department)->department_name ?? 'Empty')
    //     ->addColumn('store_name', fn($user) => optional(optional($user->employee)->store)->name ?? 'Empty')
    //     ->addColumn('position_name', fn($user) => optional(optional($user->Employee)->position)->name ?? 'Empty')
    //         ->rawColumns(['action','employee_name','company_name','department_name','store_name','position_name'])
    //         ->make(true);
    // }
//     public function getUsers(Request $request)
// {
//     $query = User::select([
//             'users.id',
//             'users.username',
//             'users.employee_id',
//         ])
//         ->with([
//             'employee.company',
//             'employee.department',
//             'employee.store',
//             'employee.position',
//         ])
//         ->employeeActiveOrPending();
//     return DataTables::eloquent($query)
//         ->addColumn('employee_name', function ($user) {
//             return optional($user->employee)->employee_name ?? 'Empty';
//         })
//         ->addColumn('company_name', function ($user) {
//             return optional(optional($user->employee)->company)->name ?? 'Empty';
//         })
//         ->addColumn('department_name', function ($user) {
//             return optional(optional($user->employee)->department)->department_name ?? 'Empty';
//         })
//         ->addColumn('store_name', function ($user) {
//             return optional(optional($user->employee)->store)->name ?? 'Empty';
//         })
//         ->addColumn('position_name', function ($user) {
//             return optional(optional($user->employee)->position)->name ?? 'Empty';
//         })
//         ->addColumn('action', function ($user) {
//             $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
//             return '
//                 <a href="' . route('editusers', $idHashed) . '" 
//                    data-bs-toggle="tooltip" 
//                    title="Edit User: ' . e($user->username) . '">
//                     <i class="fas fa-user-edit text-secondary"></i>
//                 </a>';
//         })
//         ->rawColumns(['action'])
//         ->make(true);
// }
// private function applyEmployeeStatusFilter($query)
// {
//     $allowedStatus = ['Active', 'Pending', 'Mutation']; // bisa kamu ubah

//     $query->whereHas('employee', function ($q) use ($allowedStatus) {
//         $q->whereIn('status', $allowedStatus);
//     });
// }

// public function getUsers(Request $request)
// {
//     $query = User::select([
//             'users.id',
//             'users.username',
//             'users.employee_id',
//         ])
//         ->with([
//             'employee.company',
//             'employee.department',
//             'employee.store',
//             'employee.position',
//         ]);
//          $this->applyEmployeeStatusFilter($query);
        

//     return DataTables::eloquent($query)

//         // ===== ADD COLUMN =====
//         ->addColumn('employee_name', function ($user) {
//             return optional($user->employee)->employee_name ?? 'Empty';
//         })
//         ->addColumn('company_name', function ($user) {
//             return optional(optional($user->employee)->company)->name ?? 'Empty';
//         })
//         ->addColumn('department_name', function ($user) {
//             return optional(optional($user->employee)->department)->department_name ?? 'Empty';
//         })
//         ->addColumn('store_name', function ($user) {
//             return optional(optional($user->employee)->store)->name ?? 'Empty';
//         })
//         ->addColumn('position_name', function ($user) {
//             return optional(optional($user->employee)->position)->name ?? 'Empty';
//         })

//         // ===== FILTER COLUMN (INI PENTING) =====
//         ->filterColumn('employee_name', function ($query, $keyword) {
//             $query->whereHas('employee', function ($q) use ($keyword) {
//                 $q->where('employee_name', 'like', "%{$keyword}%");
//             });
//         })
//         ->filterColumn('company_name', function ($query, $keyword) {
//             $query->whereHas('employee.company', function ($q) use ($keyword) {
//                 $q->where('name', 'like', "%{$keyword}%");
//             });
//         })
//         ->filterColumn('department_name', function ($query, $keyword) {
//             $query->whereHas('employee.department', function ($q) use ($keyword) {
//                 $q->where('department_name', 'like', "%{$keyword}%");
//             });
//         })
//         ->filterColumn('store_name', function ($query, $keyword) {
//             $query->whereHas('employee.store', function ($q) use ($keyword) {
//                 $q->where('name', 'like', "%{$keyword}%");
//             });
//         })

//         // ===== ACTION =====
//         ->addColumn('action', function ($user) {
//             $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
//             return '
//                 <a href="' . route('editusers', $idHashed) . '" 
//                    data-bs-toggle="tooltip" 
//                    title="Edit User: ' . e($user->username) . '">
//                     <i class="fas fa-user-edit text-secondary"></i>
//                 </a>';
//         })
//         ->rawColumns(['action'])
//         ->make(true);
// }
// public function getUsers(Request $request)
// {
//     $query = User::select([
//             'users.id',
//             'users.username',
//             'users.employee_id',
//             'users.employee.status',

//             'companies.name as company_name',
//             'departments.department_name',
//             'stores.name as store_name',
//             'position.name as position_name',
//         ])
//         ->leftJoin('employees_tables', 'employees_tables.id', '=', 'users.employee_id')
//         ->leftJoin('company_tables', 'company_tables.id', '=', 'employees_tables.company_id')
//         ->leftJoin('departments_tables', 'departments_tables.id', '=', 'employees_tables.department_id')
//         ->leftJoin('stores_tables', 'stores_tables.id', '=', 'employees_tables.store_id')
//         ->leftJoin('position_tables', 'position_tables.id', '=', 'employees_tables.position_id');

//     // 🔥 FILTER STATUS LANGSUNG (Active, Pending, Mutation)
//     $query->whereIn('employees_tables.status', ['Active', 'Pending', 'Mutation']);

//     return DataTables::eloquent($query)

//         // ===== ACTION SAJA =====
//         ->addColumn('action', function ($user) {
//             $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);

//             return '
//                 <a href="' . route('editusers', $idHashed) . '" 
//                    title="Edit User: ' . e($user->username) . '">
//                     <i class="fas fa-user-edit text-secondary"></i>
//                 </a>';
//         })

//         ->rawColumns(['action'])
//         ->make(true);
// }
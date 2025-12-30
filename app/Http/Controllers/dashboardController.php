<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Tickets;
use Yajra\DataTables\Facades\DataTables;

class dashboardController extends Controller
{
  public function dashboardPage()
  {
    $user = Auth::user();
    $todaysticket = Tickets::whereDate('created_at', Carbon::today())->count();
    $onprogressticket = Tickets::where('status', 'Progress')
      ->count();
    $closedticket = Tickets::where('status', 'Closed')
      ->count();
    $overdueticket = Tickets::where('status', 'Overdue')
      ->count();
    return view('pages.dashboard', compact('user', 'todaysticket', 'onprogressticket', 'closedticket', 'overdueticket'));
  }
  public function aboutUs()
  {
    return view('pages.about');
  }

public function getAllticketforadmins(Request $request)
{
    $query = Tickets::with('user.employee')
        ->select([
            'id',
            'user_id',
            'queue_number',
            'title',
            'description',
            'category',
            'status',
            'created_at', // pastikan ada
        ])
        ->whereDate('created_at', Carbon::today()); // 🔥 ticket hari ini saja

    return DataTables::eloquent($query)
        ->addColumn('employee_name', function ($ticket) {
            return optional($ticket->user?->employee)->employee_name ?? '-';
        })
        ->orderColumn('employee_name', function ($query, $order) {
            // optional
        })
        ->addColumn('action', function ($user) {
            $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);

               return '
        <a href="' . route('editopenticket', $idHashed) . '"
           class="inline-flex items-center justify-center p-2 
                  text-slate-500 hover:text-indigo-600 
                  hover:bg-indigo-50 rounded-full transition"
           title="Edit Tickets: ' . e($user->user->employee->employee_name) . '">

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
         <a href="' . route('showtickets', $idHashed) . '"
           class="inline-flex items-center justify-center p-2
                  text-slate-500 hover:text-emerald-600
                  hover:bg-emerald-50 rounded-full transition"
           title="Show Tickets: ' . e($user->user->employee->employee_name) . '">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 12s3.75-6.75 9.75-6.75
                         S21.75 12 21.75 12
                         18 18.75 12 18.75
                         2.25 12 2.25 12z" />
                <circle cx="12" cy="12" r="3.25" />
            </svg>

        </a>
    ';
})
        ->rawColumns(['action'])
        ->make(true);
}

// public function getAllticketforadmins(Request $request)
// {
//     $query = Tickets::with('user.employee')
//         ->select([
//             'id',
//             'user_id',
//             'queue_number',
//             'title',
//             'description',
//             'category',
//             'status',
//         ]);

//     return DataTables::eloquent($query)
//         ->addColumn('employee_name', function ($ticket) {
//             return optional($ticket->user?->employee)->employee_name ?? '-';
//         })
//         ->orderColumn('employee_name', function ($query, $order) {
//         })
//          ->addColumn('action', function ($user) {
//     $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);

//     return '
//         <a href="' . route('editopenticket', $idHashed) . '"
//            class="inline-flex items-center justify-center p-2 
//                   text-slate-500 hover:text-indigo-600 
//                   hover:bg-indigo-50 rounded-full transition"
//            title="Edit Tickets: ' . e($user->user->employee->employee_name) . '">

//             <svg xmlns="http://www.w3.org/2000/svg" 
//                  class="w-5 h-5" 
//                  fill="none" 
//                  viewBox="0 0 24 24" 
//                  stroke="currentColor" 
//                  stroke-width="1.8">
//                 <path stroke-linecap="round" stroke-linejoin="round"
//                       d="M16.862 3.487a2.1 2.1 0 013.001 2.949L7.125 19.174 
//                          3 21l1.826-4.125L16.862 3.487z" />
//             </svg>

//         </a>
//          <a href="' . route('showtickets', $idHashed) . '"
//            class="inline-flex items-center justify-center p-2
//                   text-slate-500 hover:text-emerald-600
//                   hover:bg-emerald-50 rounded-full transition"
//            title="Show Tickets: ' . e($user->user->employee->employee_name) . '">

//             <svg xmlns="http://www.w3.org/2000/svg"
//                  class="w-5 h-5"
//                  fill="none"
//                  viewBox="0 0 24 24"
//                  stroke="currentColor"
//                  stroke-width="1.8">
//                 <path stroke-linecap="round" stroke-linejoin="round"
//                       d="M2.25 12s3.75-6.75 9.75-6.75
//                          S21.75 12 21.75 12
//                          18 18.75 12 18.75
//                          2.25 12 2.25 12z" />
//                 <circle cx="12" cy="12" r="3.25" />
//             </svg>

//         </a>
//     ';
// })
//                 ->rawColumns(['action'])

//         ->make(true);
// }
 public function edit($hash)
    {
        $user = Tickets::all()->first(function ($u) use ($hash) {
            return substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8) === $hash;
        });
        abort_if(!$user, 404);
        return view('pages.editusers', compact('user'));
    }
//  public function show($hash)
//     {
//         $user = Tickets::all()->first(function ($u) use ($hash) {
//             return substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8) === $hash;
//         });
//         abort_if(!$user, 404);
//         return view('pages.editusers', compact('user'));
//     }
   public function show($hash)
    {
        $ticket = Tickets::with([
            'user.employee',
            'attachments',
        ])
            ->get()
            ->first(function ($ticket) use ($hash) {
                $hashedId = substr(
                    hash('sha256', $ticket->id . env('APP_KEY')),
                    0,
                    8
                );
                return hash_equals($hashedId, $hash);
            });

        if (! $ticket) {
            abort(404, 'Ticket not found');
        }

        return view('pages.showtickets', compact('ticket'));
    }
}

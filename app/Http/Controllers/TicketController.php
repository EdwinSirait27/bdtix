<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Tickets;
use Illuminate\Support\Facades\Log;
use App\Models\Ticketattachments;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Services\NextcloudService;
use PHPUnit\Framework\Attributes\Ticket;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\ProcessTicketAttachmentsJob;
use App\Jobs\SendTicketWhatsappJob;

class TicketController extends Controller
{
    public function openTicket()
    {
        return view('pages.openticket');
    }

    public function resolveTicket()
    {
        $user = Auth::user();
        $executorId = auth()->id();
        $allticket = Tickets::where('executor_id', $executorId)
            ->count();

        $overdueticket = Tickets::where('executor_id', $executorId)
            ->where('status', 'Overdue')
            ->count();

        $todaysticket = Tickets::where('executor_id', $executorId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $onprogressticket = Tickets::where('executor_id', $executorId)
            ->where('status', 'Progress')
            ->count();

        return view(
            'pages.resolvetickets',
            compact(
                'user',
                'overdueticket',
                'allticket',
                'todaysticket',
                'onprogressticket'
            )
        );
    }
    public function getResolvetickets(Request $request)
    {
        $query = Tickets::with(['user.employee', 'executor.employee'])
            ->where('executor_id', auth()->id())
            ->select([
                'id',
                'queue_number',
                'title',
                'executor_id',
                'priority',
                'finished',
                'estimation',
                'description',
                'category',
                'status',
            ]);

        return DataTables::eloquent($query)
            ->addColumn('employee_name', function ($ticket) {
                // PEMBUAT TICKET (human)
                return optional($ticket->user?->employee)->employee_name ?? '-';
            })
            ->orderColumn('employee_name', function ($query, $order) {
                // disable ordering
            })

            ->addColumn('executor_employee_name', function ($ticket) {
                // EXECUTOR
                return $ticket->executor?->employee?->employee_name ?? 'empty';
            })
            ->orderColumn('executor_employee_name', function ($query, $order) {
                // disable ordering
            })

            ->addColumn('action', function ($ticket) {
                $idHashed = substr(
                    hash('sha256', $ticket->id . env('APP_KEY')),
                    0,
                    8
                );

                return '
                <a href="' . route('editresolvetickets', $idHashed) . '"
                   class="inline-flex items-center justify-center p-2
                          text-slate-500 hover:text-indigo-600
                          hover:bg-indigo-50 rounded-full transition"
                   title="Edit Tickets: ' . e($ticket->title) . '">

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

                <a href="' . route('showresolvetickets', $idHashed) . '"
                   class="inline-flex items-center justify-center p-2
                          text-slate-500 hover:text-emerald-600
                          hover:bg-emerald-50 rounded-full transition"
                   title="Show Tickets: ' . e($ticket->title) . '">

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
    public function myTicket()
    {
        $user = Auth::user();

        $allticket = Tickets::where('user_id', auth()->id())
            ->count();
        $overdueticket = Tickets::where('user_id', auth()->id())
            ->where('status', 'Overdue')

            ->count();
        $todaysticket = Tickets::where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->count();
        $onprogressticket = Tickets::where('user_id', auth()->id())
            ->where('status', 'Progress')
            ->count();
        return view('pages.mytickets', compact('user', 'overdueticket', 'allticket', 'todaysticket', 'onprogressticket'));
    }
    public function allTickets()
    {

        $todaysticket = Tickets::whereDate('created_at', Carbon::today())->count();
        $highprior = Tickets::where('priority', 'High')->count();
        $onprogressticket = Tickets::where('status', 'Progress')
            ->count();
        return view('pages.alltickets', compact('todaysticket', 'onprogressticket', 'highprior'));
    }

    public function getAllmytickets(Request $request)
    {
        $query = Tickets::with(['user.employee', 'executor.employee', 'review'])
            ->where('user_id', auth()->id())
            ->select([
                'id',
                'queue_number',
                'title',
                'executor_id',
                'priority',
                'finished',
                'estimation',
                'description',
                'category',
                'created_at',
                'status',
            ]);
        return DataTables::eloquent($query)
            ->addColumn('employee_name', function ($ticket) {
                return optional($ticket->user?->employee)->employee_name ?? '-';
            })
            ->orderColumn('employee_name', function ($query, $order) {})
            ->addColumn('executor_employee_name', function ($ticket) {
                return $ticket->executor?->employee?->employee_name ?? 'empty';
            })
            ->orderColumn('executor_employee_name', function ($query, $order) {})
           
            ->addColumn('action', function ($ticket) {

                $idHashed = substr(hash('sha256', $ticket->id . env('APP_KEY')), 0, 8);

                // ================= EDIT BUTTON =================
                if (in_array($ticket->status, ['Progress', 'Closed'])) {

                    // 🔒 LOCKED ICON
                    $editBtn = '
            <span
                class="inline-flex items-center justify-center p-2
                       text-slate-400 cursor-not-allowed"
                title="Ticket cannot be edited due to status ' . e($ticket->status) . '">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 11h14v9H5z" />
                </svg>
            </span>';
                } else {

                    // ✏️ EDIT ICON
                    $editBtn = '
            <a href="' . route('editmytickets', $idHashed) . '"
               class="inline-flex items-center justify-center p-2
                      text-slate-500 hover:text-indigo-600
                      hover:bg-indigo-50 rounded-full transition"
               title="Edit Ticket: ' . e($ticket->title) . '">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 3.487a2.1 2.1 0 013.001 2.949
                             L7.125 19.174 3 21l1.826-4.125
                             L16.862 3.487z" />
                </svg>
            </a>';
                }

                // ================= SHOW BUTTON =================
                $showBtn = '
        <a href="' . route('showmytickets', $idHashed) . '"
           class="inline-flex items-center justify-center p-2
                  text-slate-500 hover:text-emerald-600
                  hover:bg-emerald-50 rounded-full transition"
           title="Show Ticket: ' . e($ticket->title) . '">
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
        </a>';

                // ================= REVIEW BUTTON =================
                $reviewBtn = '';

                if (in_array($ticket->status, ['Closed', 'Overdue'])) {
                    $reviewBtn = '
    <a href="' . route("reviewtickets", $idHashed) . '"
       class="inline-flex items-center justify-center p-2
              text-slate-400 hover:text-yellow-500
              hover:bg-yellow-50 rounded-full transition"
       title="Review Ticket">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-6 h-6"
             viewBox="0 0 20 20"
             fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286
                     3.966a1 1 0 00.95.69h4.173c.969 0
                     1.371 1.24.588 1.81l-3.377
                     2.455a1 1 0 00-.364 1.118l1.287
                     3.966c.3.921-.755 1.688-1.54
                     1.118l-3.377-2.455a1 1 0 00-1.175
                     0l-3.377 2.455c-.784.57-1.838
                     -.197-1.539-1.118l1.287-3.966a1
                     1 0 00-.364-1.118L2.98
                     9.393c-.783-.57-.38-1.81.588
                     -1.81h4.173a1 1 0 00.95-.69
                     l1.286-3.966z"/>
        </svg>
    </a>';
                }

                return $editBtn . $showBtn . $reviewBtn;
            })

           

            ->editColumn('created_at', function ($ticket) {
                return optional($ticket->created_at)
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i');
            })
            ->editColumn('estimation', function ($ticket) {
                return $ticket->estimation
                    ? $ticket->estimation
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i')
                    : 'empty';
            })
            ->editColumn('finished', function ($ticket) {
                return $ticket->finished
                    ? $ticket->finished
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i')
                    : 'empty';
            })



            ->rawColumns(['action'])
            ->make(true);
    }
    public function show($hash)
    {
        $userId = Auth::id();
        $ticket = Tickets::with([
            'user.employee',
            'executor.employee',
            'attachments',
        ])
            ->where('user_id', $userId)
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
        return view('pages.showmytickets', compact('ticket'));
    }
// public function reviewticket($hash)
// {
//     $userId = Auth::id();

//     Log::info('Access review ticket page - start', [
//         'hash'   => $hash,
//         'user'   => $userId,
//         'ip'     => request()->ip(),
//         'agent' => request()->userAgent(),
//     ]);

//     $ticket = Tickets::with([
//             'user.employee',
//             'attachments',
//         ])
//         ->where('user_id', $userId)
//         ->get()
//         ->first(function ($ticket) use ($hash) {
//             $hashedId = substr(
//                 hash('sha256', $ticket->id . env('APP_KEY')),
//                 0,
//                 8
//             );
//             return hash_equals($hashedId, $hash);
//         });

//     if (! $ticket) {
//         Log::warning('Review ticket access failed - ticket not found', [
//             'hash' => $hash,
//             'user' => $userId,
//         ]);

//         abort(404, 'Ticket not found');
//     }

//     Log::info('Review ticket page loaded', [
//         'ticket_id' => $ticket->id,
//         'status'    => $ticket->status,
//         'user'      => $userId,
//     ]);

//     return view('pages.reviewtickets', compact('ticket'));
// }
public function reviewticket($hash)
{
    $userId = Auth::id();
    $user   = Auth::user();

    Log::info('Access review ticket page - start', [
        'hash'  => $hash,
        'user'  => $userId,
        'roles' => $user->getRoleNames(),
    ]);

    $query = Tickets::with([
        'user.employee',
        'attachments',
    ]);

    // ✅ hanya HUMAN yang dibatasi user_id
    if ($user->hasRole('human')) {
        $query->where('user_id', $userId);
    }

    $ticket = $query->get()->first(function ($ticket) use ($hash) {
        $hashedId = substr(
            hash('sha256', $ticket->id . env('APP_KEY')),
            0,
            8
        );
        return hash_equals($hashedId, $hash);
    });

    if (! $ticket) {
        Log::warning('Review ticket access failed - ticket not found', [
            'hash' => $hash,
            'user' => $userId,
        ]);

        abort(404, 'Ticket not found');
    }

    Log::info('Review ticket page loaded', [
        'ticket_id' => $ticket->id,
        'status'    => $ticket->status,
        'user'      => $userId,
    ]);

    return view('pages.reviewtickets', compact('ticket'));
}

public function storeReview(Request $request, $hash)
{
    Log::info('Submit review attempt', [
        'hash' => $hash,
        'user' => auth()->id(),
        'ip'   => $request->ip(),
    ]);

    $ticket = Tickets::where('user_id', auth()->id())
        ->get()
        ->first(function ($ticket) use ($hash) {
            $hashedId = substr(
                hash('sha256', $ticket->id . env('APP_KEY')),
                0,
                8
            );
            return hash_equals($hashedId, $hash);
        });

    if (!$ticket) {
        Log::warning('Review submit failed - ticket not found', [
            'hash' => $hash,
            'user' => auth()->id(),
        ]);
        abort(404);
    }
     if ($ticket->user_id !== auth()->id()) {
        abort(403);
    }

    // 🔐 Status valid
    if (!in_array($ticket->status, ['Closed', 'Finished'])) {
        return back()->with('error', 'Ticket is not completed, cannot be reviewed.');
    }

    // 🔐 Harus ada executor
    if (!$ticket->executor_id) {
        return back()->with('error', 'Ticket does not have an executor yet.');
    }
    if ($ticket->review) {
        Log::warning('Review submit blocked - already reviewed', [
            'ticket_id' => $ticket->id,
            'user'      => auth()->id(),
        ]);

        return back()->with('error', 'This ticket has already been reviewed.');
    }


    // lanjut logic lama
        abort_if($ticket->user_id !== auth()->id(), 403);
    abort_if(!in_array($ticket->status, ['Closed', 'Finished']), 403);
    abort_if(!$ticket->executor_id, 422);
    abort_if($ticket->review, 409);

    $validated = $request->validate([
        'rating'  => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    $ticket->review()->create([
        'id'          => (string) Str::uuid(),
        'ticket_id'   => $ticket->id,
        'user_id'     => auth()->id(),
        'executor_id' => $ticket->executor_id,
        'rating'      => $validated['rating'],
        'comment'     => $validated['comment'] ?? null,
    ]);
    Log::info('Ticket reviewed successfully', [
        'ticket_id' => $ticket->id,
        'rating'    => $validated['rating'],
    ]);

    return back()->with('success', 'Thank you, your review has been saved successfully.');
}



    private function findTicketByHash(string $hash): Tickets
    {
        $ticket = Tickets::with('user.employee')
            ->whereRaw(
                "SUBSTRING(SHA2(CONCAT(id, ?), 256), 1, 8) = ?",
                [config('app.key'), $hash]
            )
            ->first();

        abort_if(!$ticket, 404, 'Ticket not found');

        return $ticket;
    }
    public function editmyticket(string $hash)
    {
        $ticket = $this->findTicketByHash($hash);
        $user   = auth()->user();
        if ($ticket->status === 'Closed') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'The ticket is closed and cannot be edited.');
        }
        if ($ticket->user_id !== $user->id) {
            abort(403, 'You are not allowed to access this ticket.');
        }

        return view('pages.editmytickets', compact('ticket'));

        abort(403, 'Unauthorized action.');
    }
  
    public function updatemytickets(Request $request, string $hash)
    {
        $ticket = $this->findTicketByHash($hash);
        if (in_array($ticket->status, ['Progress', 'Closed'])) {
            abort(403, 'Ticket cannot be edited');
        }
        Log::info('TICKET_UPDATE_START', [
            'ticket_id' => $ticket->id,
        ]);
        $validated = $request->validate([
            'title'        => 'required|string',
            'category'        => 'required|string',
            'description'        => 'required|string|min:5|max:500',

        ]);

        DB::transaction(function () use ($validated, $ticket) {


            $ticket->update([
                'title'        => $validated['title'],
                'category'        => $validated['category'],
                'description' => $validated['description'],
                'status'          => 'Open',
            ]);

            Log::info('TICKET_UPDATED', [
                'ticket_id' => $ticket->id,
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Ticket successfully updated');
    }

   
  
    public function showalltickets($hash)
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

        return view('pages.showalltickets', compact('ticket'));
    }
    public function editalltickets($hash)
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

        return view('pages.editalltickets', compact('ticket'));
    }
    public function getAlltickets(Request $request)
    {
        $query = Tickets::with(['user.employee', 'executor.employee'])
            ->select([
                'id',
                'user_id',
                'queue_number',
                'title',
                'executor_id',
                'priority',
                'finished',
                'estimation',
                'description',
                'category',
                'created_at',
                'status',
            ]);

        return DataTables::eloquent($query)
            ->addColumn('employee_name', function ($ticket) {
                return optional($ticket->user?->employee)->employee_name ?? '-';
            })
            ->orderColumn('employee_name', function ($query, $order) {
                // disable ordering (WAJIB)
            })
            ->addColumn('executor_employee_name', function ($ticket) {
                return $ticket->executor?->employee?->employee_name ?? 'empty';
            })
            ->orderColumn('executor_employee_name', function ($query, $order) {
                // disable ordering (WAJIB)
            })


            ->addColumn('action', function ($ticket) {
                $idHashed = substr(hash('sha256', $ticket->id . env('APP_KEY')), 0, 8);

                if (in_array($ticket->status, ['Closed'])) {

                    // 🔒 LOCKED ICON (no link)
                    $editBtn = '
            <span
                class="inline-flex items-center justify-center p-2
                       text-slate-400 cursor-not-allowed"
                title="Ticket cannot be edited due to status ' . e($ticket->status) . '">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 11h14v9H5z" />
                </svg>
            </span>';
                } else {

                    // ✏️ EDIT ICON (active)
                    $editBtn = '
            <a href="' . route('editopenticketforadmin', $idHashed) . '"
               class="inline-flex items-center justify-center p-2
                      text-slate-500 hover:text-indigo-600
                      hover:bg-indigo-50 rounded-full transition"
               title="Edit Ticket: ' . e($ticket->title) . '">
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
            </a>';
                }

                // ===== SHOW BUTTON (always available) =====
                $showBtn = '
        <a href="' . route('showopenticket', $idHashed) . '"
           class="inline-flex items-center justify-center p-2
                  text-slate-500 hover:text-emerald-600
                  hover:bg-emerald-50 rounded-full transition"
           title="Show Ticket: ' . e($ticket->title) . '">
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
        </a>';

                return $editBtn . $showBtn;
            })

            ->editColumn('created_at', function ($ticket) {
                return optional($ticket->created_at)
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i');
            })
            ->editColumn('estimation', function ($ticket) {
                return $ticket->estimation
                    ? $ticket->estimation
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i')
                    : 'empty';
            })
            ->editColumn('finished', function ($ticket) {
                return $ticket->finished
                    ? $ticket->finished
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i')
                    : 'empty';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
            'title'         => 'required|string|max:150',
            'category'      => 'required|string',
            'description'   => 'required|string|max:500',
            'attachments'   => 'nullable|array|max:3',
            'attachments.*' => 'file|max:51200|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',

        ]);
        DB::beginTransaction();
        try {
            // 🔒 aman dari race queue number
            $queueNumber = Tickets::whereDate('created_at', today())
                ->lockForUpdate()
                ->count() + 1;

            $ticket = Tickets::create([
                'id'           => (string) Str::uuid(),
                'request_uuid' => $validated['request_uuid'],
                'user_id'      => auth()->id(),
                'queue_number' => $queueNumber,
                'title'        => $validated['title'],
                'category'     => $validated['category'],
                'description'  => $validated['description'],
                'status'       => 'Open',
            ]);

            DB::commit(); // ⬅️ WAJIB selesai dulu

            /**
             * ============================
             * PREPARE TEMP FILES
             * ============================
             */
            $tempFiles = [];
            if ($request->hasFile('attachments')) {
    foreach ($request->file('attachments') as $file) {
        $path = $file->store('tmp/tickets');

        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $extension = $file->getClientOriginalExtension();

        $tempFiles[] = [
            'path' => $path,
            'name' => $originalName . '.' . $extension, // ✅ ADA TITIK
            'mime' => $file->getClientMimeType(),       // ✅ WAJIB
        ];
    }

    ProcessTicketAttachmentsJob::dispatch(
        $ticket->id,
        $tempFiles,
        auth()->id()
    )
    ->onQueue('ticket-heavy')
    ->afterCommit();
}


            // 🔔 WA HARUS SELALU DIKIRIM
            SendTicketWhatsappJob::dispatch($ticket->id)
                ->onQueue('notification')
                ->afterCommit();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Ticket has been successfully submitted and is being processed.');
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::critical('TICKET_STORE_FAILED', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'failed send ticket');
        }
    }
}

 
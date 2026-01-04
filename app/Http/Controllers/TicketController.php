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
            ->orderColumn('executor_employee_name', function ($query, $order) {
                // disable ordering (WAJIB)
            })
    //         ->addColumn('action', function ($user) {
    //             $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
    //             return '
    //     <a href="' . route('editmytickets', $idHashed) . '"
    //        class="inline-flex items-center justify-center p-2
    //               text-slate-500 hover:text-indigo-600
    //               hover:bg-indigo-50 rounded-full transition"
    //        title="Edit Tickets: ' . e($user->title) . '">

    //         <svg xmlns="http://www.w3.org/2000/svg"
    //              class="w-5 h-5"
    //              fill="none"
    //              viewBox="0 0 24 24"
    //              stroke="currentColor"
    //              stroke-width="1.8">
    //             <path stroke-linecap="round" stroke-linejoin="round"
    //                   d="M16.862 3.487a2.1 2.1 0 013.001 2.949L7.125 19.174
    //                      3 21l1.826-4.125L16.862 3.487z" />
    //         </svg>

    //     </a>
    //      <a href="' . route('showmytickets', $idHashed) . '"
    //        class="inline-flex items-center justify-center p-2
    //               text-slate-500 hover:text-emerald-600
    //               hover:bg-emerald-50 rounded-full transition"
    //        title="Show Tickets: ' . e($user->title) . '">

    //         <svg xmlns="http://www.w3.org/2000/svg"
    //              class="w-5 h-5"
    //              fill="none"
    //              viewBox="0 0 24 24"
    //              stroke="currentColor"
    //              stroke-width="1.8">
    //             <path stroke-linecap="round" stroke-linejoin="round"
    //                   d="M2.25 12s3.75-6.75 9.75-6.75
    //                      S21.75 12 21.75 12
    //                      18 18.75 12 18.75
    //                      2.25 12 2.25 12z" />
    //             <circle cx="12" cy="12" r="3.25" />
    //         </svg>

    //     </a>
    // ';
    //         })
    ->addColumn('action', function ($ticket) {

    $idHashed = substr(hash('sha256', $ticket->id . env('APP_KEY')), 0, 8);

    // ===== EDIT BUTTON =====
    if (in_array($ticket->status, ['Progress', 'Closed'])) {

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
                          d="M16.862 3.487a2.1 2.1 0 013.001 2.949L7.125 19.174
                             3 21l1.826-4.125L16.862 3.487z" />
                </svg>
            </a>';
    }

    // ===== SHOW BUTTON (always available) =====
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

    return $editBtn . $showBtn;
})

            ->addColumn('review_action', function ($ticket) {

                // ticket belum selesai
                if (!in_array($ticket->status, ['Closed', 'Overdue'])) {
                    return '<span class="text-gray-400">-</span>';
                }

                // sudah direview
                if ($ticket->review) {
                    return '
            <span class="inline-flex items-center text-emerald-600 font-semibold">
                ★ ' . $ticket->review->rating . '/5
            </span>
        ';
                }

                // bisa review
                $idHashed = substr(hash('sha256', $ticket->id . env('APP_KEY')), 0, 8);

                return '
        <a href="' . route('reviewtickets', $idHashed) . '"
           class="inline-flex items-center px-3 py-1
                  text-sm text-yellow-700 bg-yellow-100
                  hover:bg-yellow-200 rounded-full transition">
           ⭐ Review
        </a>
    ';
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
    public function reviewticket($hash)
    {
        $userId = Auth::id();
        $ticket = Tickets::with([
            'user.employee',
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

        return view('pages.reviewtickets', compact('ticket'));
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
    // public function edit($hash)
    // {
    //     $userId = Auth::id();
    //     $ticket = Tickets::with([
    //         'user.employee',
    //         'attachments',
    //     ])
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
    //         abort(404, 'Ticket not found');
    //     }

    //     return view('pages.editmytickets', compact('ticket'));
    // }
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

    public function storeReview(Request $request, Tickets $ticket)
    {
        // hanya pengaju ticket
        abort_if($ticket->user_id !== auth()->id(), 403);

        // ticket harus selesai
        abort_if(!in_array($ticket->status, ['closed', 'finished']), 403);

        // ticket harus punya executor
        abort_if(!$ticket->executor_id, 422);

        // 1 ticket = 1 review
        abort_if($ticket->review, 409);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $ticket->review()->create([
            'user_id'     => auth()->id(),
            'executor_id' => $ticket->executor_id,
            ...$validated
        ]);

        Log::info('Ticket reviewed', [
            'ticket_id'   => $ticket->id,
            'rating'      => $validated['rating'],
            'executor_id' => $ticket->executor_id,
            'reviewer'    => auth()->id(),
        ]);

        return back()->with('success', 'Terima kasih, review Anda berhasil disimpan 🙏');
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


            ->addColumn('action', function ($user) {
                $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);

                return '
        <a href="' . route('editopenticketforadmin', $idHashed) . '"
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
         <a href="' . route('editopenticketforadmin', $idHashed) . '"
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
            'title'         => 'required|string|max:150',
            'category'      => 'required|string',
            'description'   => 'required|string|max:500',
            'attachments'   => 'nullable|array|max:3',
            'attachments.*' => 'file|max:51200|mimes:jpg,jpeg,png,pdf,doc,docx',
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

                    $tempFiles[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType(),
                    ];
                }

                // 📦 job berat (optional)
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

    // public function store(Request $request)
    // {
    //     Log::info('TICKET_STORE_START', [
    //         'user_id' => auth()->id(),
    //         'ip'      => $request->ip(),
    //         'has_file' => $request->hasFile('attachments'),
    //         'file_count' => is_array($request->file('attachments'))
    //             ? count($request->file('attachments'))
    //             : 0,
    //     ]);

    //     // =========================
    //     // VALIDATION
    //     // =========================
    //     try {
    //         $validated = $request->validate([
    //             'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
    //             'title'         => 'required|string|min:1|max:150',
    //             'category'      => 'required|string',
    //             'description'   => 'required|string|min:1|max:500',
    //             'attachments'   => 'nullable|array|min:1|max:3',
    //             'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    //         ]);

    //         Log::info('VALIDATION_SUCCESS', $validated);
    //     } catch (\Throwable $e) {
    //         Log::error('VALIDATION_FAILED', [
    //             'error' => $e->getMessage(),
    //         ]);
    //         throw $e;
    //     }

    //     try {
    //         DB::transaction(function () use ($request, $validated, &$ticket) {

    //             Log::info('DB_TRANSACTION_START');

    //             // =========================
    //             // QUEUE NUMBER
    //             // =========================
    //             $queueNumber = Tickets::whereDate('created_at', Carbon::today())
    //                 ->lockForUpdate()
    //                 ->count() + 1;

    //             Log::info('QUEUE_NUMBER_GENERATED', [
    //                 'queue_number' => $queueNumber
    //             ]);

    //             // =========================
    //             // CREATE TICKET
    //             // =========================
    //             $ticket = Tickets::create([
    //                 'id'           => (string) Str::uuid(),
    //                 'request_uuid' => $validated['request_uuid'],
    //                 'user_id'      => auth()->id(),
    //                 'queue_number' => $queueNumber,
    //                 'title'        => $validated['title'],
    //                 'category'     => $validated['category'],
    //                 'description'  => $validated['description'],
    //                 'status'       => 'Open',
    //             ]);

    //             Log::info('TICKET_CREATED', [
    //                 'ticket_id' => $ticket->id
    //             ]);

    //             // =========================
    //             // ATTACHMENT PROCESS
    //             // =========================
    //             Log::info('ATTACHMENT_CHECK', [
    //                 'has_file' => $request->hasFile('attachments')
    //             ]);

    //             if ($request->hasFile('attachments')) {

    //                 $categoryFolder = Str::slug($ticket->category);
    //                 $userFolder     = Str::slug(auth()->user()->username);
    //                 $ticketFolder   = $ticket->id;

    //                 $basePath = "ticket/{$categoryFolder}/{$userFolder}/{$ticketFolder}";

    //                 Log::info('NEXTCLOUD_PATH_PREPARED', [
    //                     'base_path' => $basePath
    //                 ]);

    //                 try {
    //                     NextcloudService::makeDir('ticket');
    //                     NextcloudService::makeDir("ticket/{$categoryFolder}");
    //                     NextcloudService::makeDir("ticket/{$categoryFolder}/{$userFolder}");
    //                     NextcloudService::makeDir($basePath);

    //                     Log::info('NEXTCLOUD_DIRECTORIES_CREATED');
    //                 } catch (\Throwable $e) {
    //                     Log::error('NEXTCLOUD_MKDIR_FAILED', [
    //                         'error' => $e->getMessage(),
    //                         'path'  => $basePath
    //                     ]);
    //                     throw $e;
    //                 }

    //                 foreach ($request->file('attachments') as $index => $file) {
    //                     $filename = time() . '_' . $file->getClientOriginalName();

    //                     Log::info('UPLOAD_FILE_START', [
    //                         'index'    => $index,
    //                         'filename' => $filename,
    //                         'mime'     => $file->getMimeType(),
    //                         'size'     => $file->getSize(),
    //                     ]);
    //                     try {
    //                         NextcloudService::upload(
    //                             $basePath,
    //                             $filename,
    //                             file_get_contents($file->getRealPath()),
    //                             $file->getMimeType()
    //                         );

    //                         Log::info('UPLOAD_FILE_SUCCESS', [
    //                             'filename' => $filename
    //                         ]);

    //                         Ticketattachments::create([
    //                             'id'        => (string) Str::uuid(),
    //                             'ticket_id' => $ticket->id,
    //                             'file_name' => $filename,
    //                             'file_path' => "{$basePath}/{$filename}",
    //                         ]);

    //                         Log::info('ATTACHMENT_DB_CREATED', [
    //                             'filename' => $filename
    //                         ]);
    //                     } catch (\Throwable $e) {
    //                         Log::error('UPLOAD_FILE_FAILED', [
    //                             'filename' => $filename,
    //                             'error'    => $e->getMessage()
    //                         ]);
    //                         throw $e;
    //                     }
    //                 }

    //                 // =========================
    //                 // SHARE FOLDER
    //                 // =========================
    //                 try {
    //                     $shareUrl = NextcloudService::shareFolder($basePath);

    //                     Log::info('NEXTCLOUD_SHARE_SUCCESS', [
    //                         'url' => $shareUrl
    //                     ]);

    //                     $ticket->update([
    //                         'attachment_folder' => $basePath,
    //                         'attachment_url'    => $shareUrl,
    //                     ]);
    //                 } catch (\Throwable $e) {
    //                     Log::error('NEXTCLOUD_SHARE_FAILED', [
    //                         'path'  => $basePath,
    //                         'error' => $e->getMessage(),
    //                     ]);
    //                     throw $e;
    //                 }
    //             }

    //             Log::info('DB_TRANSACTION_END');
    //         });

    //         $ticket->refresh();
    //         $hash = substr(
    //             hash('sha256', $ticket->id . config('app.key')),
    //             0,
    //             8
    //         );

    //         $editTicketUrl = route('editopenticketforadmin', $hash);

    //         Log::info('TICKET_REFRESHED', ['ticket_id' => $ticket->id]);

    //         // =========================
    //         // WHATSAPP NOTIFICATION
    //         // =========================
    //         try {
    //             Log::info('WA_SEND_START', [
    //                 'ticket_id' => $ticket->id
    //             ]);
    //             $formattedDate = $ticket->created_at
    //                 ->timezone('Asia/Makassar')
    //                 ->format('d-m-Y H:i');

    //             $userName = auth()->user()->employee->employee_name
    //                 ?? auth()->user()->employee->store->name
    //                 ?? auth()->user()->username;
    //             $locationName = auth()->user()->employee->store->name;
    //             $phoneNumber = auth()->user()->employee->telp_number;
    //             $message =
    //                 "*New Ticket*\n" .
    //                 "Date: {$formattedDate}\n" .
    //                 "Queue: {$ticket->queue_number}\n" .
    //                 "Title: {$ticket->title}\n" .
    //                 "User: {$userName}\n" .
    //                 "Location: {$locationName}\n" .
    //                 "Phone Number: {$phoneNumber}\n" .
    //                 "Category: {$ticket->category}\n" .
    //                 "Description: {$ticket->description}\n" .
    //                 "*Tickets Edit Link*\n" .
    //                 "{$editTicketUrl}";
    //             if (!empty($ticket->attachment_url)) {
    //                 $message .= "\nAttachments:\n{$ticket->attachment_url}";
    //             }
    //             Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
    //                 'group_id' => '120363405189832865@g.us',
    //                 'text'     => $message,
    //             ]);
    //             Log::info('WA_SEND_SUCCESS');
    //         } catch (\Throwable $e) {
    //             Log::warning('WA_SEND_FAILED', [
    //                 'error' => $e->getMessage(),
    //             ]);
    //         }
    //         Log::info('TICKET_STORE_SUCCESS', [
    //             'ticket_id' => $ticket->id
    //         ]);

    //         return redirect()->route('openticket')
    //             ->with('success', 'Ticket successfully submitted');
    //     } catch (\Throwable $e) {
    //         Log::critical('TICKET_STORE_FAILED', [
    //             'user_id' => auth()->id(),
    //             'error'   => $e->getMessage(),
    //             'trace'   => $e->getTraceAsString(),
    //         ]);

    //         return redirect()->route('openticket')
    //             ->with('error', 'Ticket failed to submitted');
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
    //         'title'         => 'required|string|max:150',
    //         'category'      => 'required|string',
    //         'description'   => 'required|string|max:500',
    //         'attachments'   => 'nullable|array|max:3',
    //         'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $queueNumber = Tickets::whereDate('created_at', today())
    //             ->lockForUpdate()
    //             ->count() + 1;

    //         $ticket = Tickets::create([
    //             'id'           => (string) Str::uuid(),
    //             'request_uuid' => $validated['request_uuid'],
    //             'user_id'      => auth()->id(),
    //             'queue_number' => $queueNumber,
    //             'title'        => $validated['title'],
    //             'category'     => $validated['category'],
    //             'description'  => $validated['description'],
    //             'status'       => 'Open',
    //         ]);

    //         DB::commit();

    //         /** 🔥 DISPATCH ASYNC JOB */
    //         if ($request->hasFile('attachments')) {
    //             ProcessTicketAttachmentsJob::dispatch(
    //                 $ticket->id,
    //                 $request->file('attachments'),
    //                 auth()->user()
    //             )->onQueue('ticket-heavy');
    //         }

    //         SendTicketWhatsappJob::dispatch($ticket->id)
    //             ->onQueue('notification');

    //         return redirect()
    //             ->route('openticket')
    //             ->with('success', 'Ticket berhasil dikirim & sedang diproses');

    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         Log::critical('TICKET_STORE_FAILED', [
    //             'error' => $e->getMessage()
    //         ]);

    //         return back()->with('error', 'Gagal mengirim ticket');
    //     }
    // }



    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
    //         'title'         => 'required|string|max:150',
    //         'category'      => 'required|string',
    //         'description'   => 'required|string|max:500',
    //         'attachments'   => 'nullable|array|max:3',
    //         'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $queueNumber = Tickets::whereDate('created_at', today())
    //             ->lockForUpdate()
    //             ->count() + 1;

    //         $ticket = Tickets::create([
    //             'id'           => (string) Str::uuid(),
    //             'request_uuid' => $validated['request_uuid'],
    //             'user_id'      => auth()->id(),
    //             'queue_number' => $queueNumber,
    //             'title'        => $validated['title'],
    //             'category'     => $validated['category'],
    //             'description'  => $validated['description'],
    //             'status'       => 'Open',
    //         ]);

    //         DB::commit();

    //         /**
    //          * ============================
    //          * STORE TEMP FILES
    //          * ============================
    //          */
    //         $tempFiles = [];

    //         if ($request->hasFile('attachments')) {
    //             foreach ($request->file('attachments') as $file) {
    //                 $path = $file->store('tmp/tickets');

    //                 $tempFiles[] = [
    //                     'path' => $path,
    //                     'name' => $file->getClientOriginalName(),
    //                     'mime' => $file->getMimeType(),
    //                 ];
    //             }

    //             // 🔥 dispatch HANYA jika ada file
    //             ProcessTicketAttachmentsJob::dispatch(
    //                 $ticket->id,
    //                 $tempFiles,
    //                 auth()->id()
    //             )->onQueue('ticket-heavy');
    //         }

    //         // 🔔 WA notification (tidak blocking)
    //         SendTicketWhatsappJob::dispatch($ticket->id)
    //             ->onQueue('notification');

    //         return redirect()
    //             ->route('dashboard')
    //             ->with('success', 'Ticket has been successfully submitted and is being processed.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         Log::critical('TICKET_STORE_FAILED', [
    //             'error' => $e->getMessage()
    //         ]);

    //         return back()->with('error', 'failed send ticket');
    //     }
    // }
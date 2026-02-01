<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\NextcloudService;
use Illuminate\Support\Facades\Http;

class dashboardController extends Controller
{

    public function dashboardPage()
    {
        $user = Auth::user();
        $adminCount = User::role('admin')->count();
        $todaysticket = Tickets::whereDate('created_at', Carbon::today())->count();
        $highprior = Tickets::where('priority', 'High')->count();
        $assignedtoyou = Tickets::where('executor_id', auth()->id())->count();
        $finishedtickettoyou = Tickets::whereNotNull('finished')
            ->where('executor_id', auth()->id())
            ->count();
        $onprogressticket = Tickets::where('status', 'Progress')->count();
        $opentickets = Tickets::where('status', 'Open')->count();
        $closedtickets = Tickets::where('status', 'Closed')->count();

        $closedticket = Tickets::whereNotNull('finished')->count();

        // 🔴 LIVE MONITORING (Overdue)
        $overdueticket = Tickets::where('status', 'Overdue')->count();

        // ✅ SLA FINAL (hanya ticket selesai)
        $totalSlaTickets = Tickets::whereNotNull('executor_id')
            ->whereNotNull('estimation')
            ->whereNotNull('finished')
            ->count();

        $slaCompliantTickets = Tickets::whereNotNull('executor_id')
            ->whereNotNull('estimation')
            ->whereNotNull('finished')
            ->whereColumn('finished', '<=', 'estimation')
            ->count();

        $slaCompliance = $totalSlaTickets > 0
            ? round(($slaCompliantTickets / $totalSlaTickets) * 100, 2)
            : 0;
        //untuk human 
        $userhuman = Auth::user();

        $alltickethuman = Tickets::where('user_id', auth()->id())
            ->count();
        $overduetickethuman = Tickets::where('user_id', auth()->id())
            ->where('status', 'Overdue')

            ->count();
        $todaystickethuman = Tickets::where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->count();
        $onprogresstickethuman = Tickets::where('user_id', auth()->id())
            ->where('status', 'Progress')
            ->count();
        $closedtickethuman = Tickets::where('user_id', auth()->id())
            ->where('status', 'Closed')
            ->count();


        // =========================
        // 📅 FILTER INPUT
        // =========================
        $month     = request('month');     // ex: 2026-01
        $quarter   = request('quarter');   // ex: Q1
        $year      = request('year');      // ex: 2026
        $dateFrom  = request('from');      // ex: 2026-01-01
        $dateTo    = request('to');        // ex: 2026-01-31
        $category  = request('category');  // ex: Network
        $categories = Tickets::distinct()->pluck('category');

        // =========================
        // 📦 BASE TICKETS QUERY
        // =========================
        $ticketBase = Tickets::query();

        // ====== FILTERS ======

        // Per bulan
        if ($month) {
            $ticketBase->whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2));
        }

        // Per quarter
        if ($quarter && $year) {
            $qMonths = [
                'Q1' => [1, 2, 3],
                'Q2' => [4, 5, 6],
                'Q3' => [7, 8, 9],
                'Q4' => [10, 11, 12],
            ];
            $ticketBase->whereYear('created_at', $year)
                ->whereIn(DB::raw('MONTH(created_at)'), $qMonths[$quarter]);
        }
        // Per date range
        // if ($dateFrom && $dateTo) {
        //     $ticketBase->whereBetween('created_at', [$dateFrom, $dateTo]);
        // } elseif ($dateFrom) {
        //     $ticketBase->where('created_at', '>=', $dateFrom);
        // } elseif ($dateTo) {
        //     $ticketBase->where('created_at', '<=', $dateTo);
        // }
        if ($dateFrom && $dateTo) {
            $ticketBase->whereBetween('created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo   . ' 23:59:59',
            ]);
        } elseif ($dateFrom) {
            $ticketBase->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $ticketBase->where('created_at', '<=', $dateTo . ' 23:59:59');
        }


        // Per kategori
        if ($category) {
            $ticketBase->where('category', $category);
        }


        // =========================
        // 👤 ALL EXECUTORS
        // =========================
        $executorIds = DB::connection('mysql')
            ->table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'executor')
            ->pluck('model_id');

        $executors = User::on('hrx')
            ->with('employee')
            ->whereIn('id', $executorIds)
            ->get();


        // =========================
        // 📊 RESPONSE TIME (Open → Progress)
        // =========================
        $avgResponseRaw = (clone $ticketBase)
            ->whereNotNull('progressed_at')
            ->whereNotNull('executor_id')
            ->select(
                'executor_id',
                'priority',
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, progressed_at)) as avg_minutes'),
                DB::raw('COUNT(*) as total_ticket')
            )
            ->groupBy('executor_id', 'priority')
            ->get()
            ->groupBy('executor_id');


        // =========================
        // 📊 RESOLUTION TIME (Progress → Closed)
        // =========================
        $avgResolutionRaw = (clone $ticketBase)
            ->whereNotNull('progressed_at')
            ->whereNotNull('finished')
            ->whereNotNull('executor_id')
            ->select(
                'executor_id',
                'priority',
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, progressed_at, finished)) as avg_minutes'),
                DB::raw('COUNT(*) as total_ticket')
            )
            ->groupBy('executor_id', 'priority')
            ->get()
            ->groupBy('executor_id');


        // =========================
        // 🎚 PRIORITIES
        // =========================
        // $priorities = Tickets::distinct()->pluck('priority');
        $order = ["Low", "Medium", "High"];
        $priorities = Tickets::distinct()
            ->pluck('priority')
            ->sort(fn($a, $b) => array_search($a, $order) <=> array_search($b, $order))
            ->values();
        // =========================
        // 📦 AGGREGATE EXECUTOR
        // =========================
        $executorStats = $executors->map(function ($user) use ($avgResponseRaw, $avgResolutionRaw, $priorities) {

            $responseRows = collect($avgResponseRaw[$user->id] ?? [])->keyBy('priority');
            $resolutionRows = collect($avgResolutionRaw[$user->id] ?? [])->keyBy('priority');

            $responseByPriority = collect($priorities)->mapWithKeys(
                fn($p) =>
                [$p => [
                    'avg'   => round($responseRows[$p]->avg_minutes ?? 0, 1),
                    'total' => $responseRows[$p]->total_ticket ?? 0,
                ]]
            );

            $resolutionByPriority = collect($priorities)->mapWithKeys(
                fn($p) =>
                [$p => [
                    'avg'   => round($resolutionRows[$p]->avg_minutes ?? 0, 1),
                    'total' => $resolutionRows[$p]->total_ticket ?? 0,
                ]]
            );

            return [
                'id' => $user->id,
                'username' => $user->username,
                'name' => optional($user->employee)->employee_name ?? $user->username,
                'response_by_priority'   => $responseByPriority,
                'resolution_by_priority' => $resolutionByPriority,
            ];
        });

        return view('pages.dashboard', compact(
            'userhuman',
            'closedtickets',
            'alltickethuman',
            'overduetickethuman',
            'todaystickethuman',
            'finishedtickettoyou',
            'onprogresstickethuman',
            'user',
            'assignedtoyou',
            'todaysticket',
            'onprogressticket',
            'opentickets',
            'closedticket',
            'overdueticket',
            'adminCount',
            'slaCompliance',
            'executors',
            'priorities',
            'categories',
            'closedtickethuman',
            'executorStats'
        ));
    }
    // testing
    // public function index(){
    //     $opentickets = Tickets::where('status','Open')->count();
    //     return view('pages.dashboard',compact('opentickets'));
    // }
    public function aboutUs()
    {
        return view('pages.about');
    }

    public function getAllticketforadmins(Request $request)
    {
        $query = Tickets::with('user.employee', 'user.employee.store', 'executor.employee')
            ->select([
                'id',
                'user_id',
                'queue_number',
                'title',
                'description',
                'progressed_at',
                'estimation',
                'estimation_to',
                'executor_id',
                'category',
                'priority',
                'finished',
                'status',
                'created_at'
            ]);
        // =========================
        // SEARCH (for mobile ajax)
        // =========================
        $search = $request->input('search.value');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('queue_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }
        // =========================
        // FILTER: STATUS
        // =========================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->status === 'On Progress') {
            $query->where('status', 'On Progress');
        }
        if ($request->filteropen === 'Open') {
            $query->where('status', 'Open');
        }
        if ($request->filterprogress === 'Progress') {
            $query->where('status', 'Progress');
        }
        if ($request->filterclosed === 'Closed') {
            $query->where('status', 'Closed');
        }
        if ($request->filteroverdue === 'Overdue') {
            $query->where('status', 'Overdue');
        }

        if ($request->filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        }


        // =========================
        // FILTER: CATEGORY
        // =========================
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // =========================
        // FILTER: DATE RANGE
        // =========================
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59',
            ]);
        }
        // else {
        //     // default: hari ini
        //     $query->whereDate('created_at', Carbon::today());
        // }

        // =========================
        // FILTER: EMPLOYEE
        // =========================
        if ($request->filled('employee_id')) {
            $query->whereHas('user.employee', function ($q) use ($request) {
                $q->where('id', $request->employee_id);
            });
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('employee_name', function ($ticket) {
                return optional($ticket->user?->employee)->employee_name ?? '-';
            })
            ->addColumn('store_name', function ($ticket) {
                return optional($ticket->user?->employee->store)->name ?? '-';
            })
            // ->addColumn('executor_name', function ($ticket) {
            //     return optional($ticket->executor?->employee)->employee_name ?? '-';
            // })
            ->addColumn('executor_employee_name', function ($ticket) {
                return $ticket->executor?->employee?->employee_name ?? 'empty';
            })
            ->orderColumn('executor_employee_name', function ($query, $order) {})


            ->orderColumn('employee_name', function ($query, $order) {
                $query->join('users', 'users.id', '=', 'tickets.user_id')
                    ->join('employees', 'employees.id', '=', 'users.employee_id')
                    ->orderBy('employees.employee_name', $order);
            })
            ->editColumn('created_at', function ($ticket) {
                return optional($ticket->created_at)
                    ->timezone('Asia/Makassar')
                    ->translatedFormat('d F Y H:i');
            })
            // ->editColumn('progressed_at', function ($ticket) {
            //     return optional($ticket->progressed_at)
            //         ->timezone('Asia/Makassar')
            //         ->translatedFormat('d F Y H:i');
            // })
            // ->editColumn('estimation', function ($ticket) {
            //     return optional($ticket->estimation)
            //         ->timezone('Asia/Makassar')
            //         ->translatedFormat('d F Y H:i');
            // })
            // ->editColumn('estimation_to', function ($ticket) {
            //     return optional($ticket->estimation)
            //         ->timezone('Asia/Makassar')
            //         ->translatedFormat('d F Y H:i');
            // })
              ->editColumn('progressed_at', function ($ticket) {
                return $ticket->progressed_at
                    ? $ticket->progressed_at->timezone('Asia/Makassar')->translatedFormat('d F Y H:i')
                    : '-';
            })
              ->editColumn('estimation', function ($ticket) {
                return $ticket->estimation
                    ? $ticket->estimation->timezone('Asia/Makassar')->translatedFormat('d F Y H:i')
                    : '-';
            })
              ->editColumn('estimation_to', function ($ticket) {
                return $ticket->estimation_to
                    ? $ticket->estimation_to->timezone('Asia/Makassar')->translatedFormat('d F Y H:i')
                    : '-';
            })
            
            ->editColumn('finished', function ($ticket) {
                return $ticket->finished
                    ? $ticket->finished->timezone('Asia/Makassar')->translatedFormat('d F Y H:i')
                    : '-';
            })

            ->addColumn('action', function ($ticket) {
                $idHashed = substr(hash('sha256', $ticket->id . env('APP_KEY')), 0, 8);
                $employee = e($ticket->user->employee->employee_name ?? '-');
                $isClosed = $ticket->status === 'Closed';
                // =========================
                // CHECK 1 MENIT DARICREATED_AT
                // =========================
                $created = $ticket->created_at;
                $allowed = $created->copy()->addMinute(); // created + 1 menit
                $canEdit = now()->greaterThanOrEqualTo($allowed);

                // ===== EDIT BUTTON =====
                if ($isClosed) {
                    $editBtn = '
        <span
            class="inline-flex items-center justify-center p-2
                   text-slate-400 bg-slate-700/40
                   rounded-full cursor-not-allowed"
            title="Ticket already closed">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 10.5V7.5a4.5 4.5 0 10-9 0v3
                       m-.75 0h10.5
                       a1.5 1.5 0 011.5 1.5v6
                       a1.5 1.5 0 01-1.5 1.5H6.75
                       a1.5 1.5 0 01-1.5-1.5v-6
                       a1.5 1.5 0 011.5-1.5z" />
            </svg>
        </span>
    ';
                } elseif (!$canEdit) {
                    // Jika belum lewat 1 menit
                    $editBtn = '
            <span class="inline-flex items-center justify-center p-2
                        text-slate-400 bg-slate-700/40 rounded-full cursor-not-allowed"
                  title="Edit available after 1 minute">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 3.487a2.1 2.1 0 013.001 2.949L7.125 19.174 
                        3 21l1.826-4.125L16.862 3.487z" />
                </svg>
            </span>
        ';
                } else {
                    // Jika boleh diedit
                    $editBtn = '
            <a href="' . route('editopenticketforadmin', $idHashed) . '"
                class="inline-flex items-center justify-center p-2 
                       text-slate-500 hover:text-indigo-600 
                       hover:bg-indigo-50 rounded-full transition"
                title="Edit Tickets: ' . $employee . '">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 3.487a2.1 2.1 0 013.001 2.949L7.125 19.174 
                        3 21l1.826-4.125L16.862 3.487z" />
                </svg>
            </a>
        ';
                }


                // ===== SHOW BUTTON (always active) =====
                $showBtn = '
        <a href="' . route('showopenticket', $idHashed) . '"
           class="inline-flex items-center justify-center p-2
                  text-slate-500 hover:text-emerald-600
                  hover:bg-emerald-50 rounded-full transition"
           title="Show Tickets: ' . $employee . '">

           <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12s3.75-6.75 9.75-6.75
                       S21.75 12 21.75 12
                       18 18.75 12 18.75
                       2.25 12 2.25 12z" />
                <circle cx="12" cy="12" r="3.25" />
           </svg>
        </a>
    ';

                return $editBtn . $showBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    private function findTicketByHash(string $hash): Tickets
    {
        $ticket = Tickets::with('user.employee')
            ->whereRaw(
                "SUBSTRING(SHA2(CONCAT(id, ?), 256), 1, 8) = ?",
                [config('app.key'), $hash]
            )
            ->first();

        abort_if(!$ticket, 404, 'Ticket tidak ditemukan');

        return $ticket;
    }

    public function edit($hash)
    {
        $ticket = Tickets::with([
            'user.employee',
            'executor.employee',
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
        if ($ticket->status === 'Closed') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Ticket Closed');
        }


        // ✅ ROLE CHECK
        if (auth()->user()->hasRole('human')) {
            return redirect()
                ->route('showmytickets', $hash)
                ->with('error', 'You are not allowed to edit this ticket');
        }


        $createdat = optional($ticket->created_at)
            ->timezone('Asia/Makassar')
            ->translatedFormat('d F Y H:i');
        // admin & executor lanjut ke edit page
        return view('pages.editopenticketforadmin', compact('ticket', 'createdat'));
    }


    // public function show(string $hash)
    // {
    //   $ticket = $this->findTicketByHash($hash);
    //   return view('pages.showopenticket', compact('ticket'));
    // }
    public function show($hash)
    {
        $ticket = Tickets::with([
            'user.employee',
            'executor.employee',
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
        if (auth()->user()->hasRole('human')) {
            return redirect()
                ->route('showmytickets', $hash)
                ->with('error', 'You are not allowed to edit this ticket');
        }
        return view('pages.showopenticket', compact('ticket'));
    }

    private function generateTicketHash(string $ticketId): string
    {
        return substr(
            hash('sha256', $ticketId . config('app.key')),
            0,
            8
        );
    }
    public function update(Request $request, string $hash)
    {
        $ticket = $this->findTicketByHash($hash);
        $oldStatus = $ticket->status;
        Log::info('TICKET_UPDATE_START', [
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
        ]);

        // =========================
        // VALIDATION
        // =========================
        $validated = $request->validate([
            'category'        => 'required|string',
            'notes_executor' => 'required|string|min:5|max:500',
            'priority'        => 'required|string',
            'finished'        => 'nullable|date',
            'estimation'      => 'nullable|date',
            'estimation_to'      => 'nullable|date',
        ]);
        // =========================
        // STATUS SYNC (SERVER SIDE)
        // =========================
        if ($ticket->status === 'Closed') {
            abort(403, 'Ticket sudah closed');
        }

        if ($ticket->status === 'Open') {
            // TAKE TICKET
            $status   = 'Progress';
            $finished = null;
            $progressedAt = now();
        } elseif ($ticket->status === 'Progress') {
            // CLOSE TICKET
            $status   = 'Closed';
            $finished = now();
            $progressedAt = $ticket->progressed_at;
        } 
        elseif ($ticket->status === 'Overdue') {
            // OVERDUE TO PROGRESS
            $status   = 'Progress';
            $finished = NULL;
             $progressedAt = $ticket->progressed_at;
        } else {
            abort(403, 'Status ticket tidak valid');
        }
        DB::transaction(function () use ($validated, $ticket, $status, $finished, $progressedAt, $oldStatus) {

            $estimation = !empty($validated['estimation'])
                ? Carbon::parse($validated['estimation'])
                : null;
            $estimationTo = !empty($validated['estimation_to'])
                ? Carbon::parse($validated['estimation_to'])
                : null;

            $data = [
                'category'        => $validated['category'],
                'notes_executor' => $validated['notes_executor'],
                'status'          => $status,
                'priority'        => $validated['priority'],
                'finished'        => $finished,
                'estimation'      => $estimation,
                'estimation_to'      => $estimationTo,
                'executor_id'     => auth()->id(),
            ];

            // ✅ hanya saat Open → Progress
            if ($oldStatus === 'Open' && $status === 'Progress') {
                $data['progressed_at'] = $progressedAt;
            }

            $ticket->update($data);

            Log::info('TICKET_UPDATED', [
                'ticket_id'      => $ticket->id,
                'old_status'     => $oldStatus,
                'new_status'     => $status,
                'progressed_at' => $data['progressed_at'] ?? $ticket->progressed_at,
            ]);
        });

        $ticket->refresh();
        try {
            $hash = $this->generateTicketHash($ticket->id);

            $adminUrl  = route('editopenticketforadmin', $hash);
            $reviewUrl = route('reviewtickets', $hash);

            $executorName = auth()->user()->employee->employee_name
                ?? auth()->user()->username;

            $formattedDate = $ticket->created_at
                ?->timezone('Asia/Makassar')
                ?->format('d-m-Y H:i') ?? '-';

            $finishedDate = $ticket->finished
                ?->timezone('Asia/Makassar')
                ?->format('d-m-Y H:i') ?? '-';

            $estimationDate = $ticket->estimation
                ?->timezone('Asia/Makassar')
                ?->format('d-m-Y H:i') ?? '-';
            $estimationToDate = $ticket->estimation_to
                ?->timezone('Asia/Makassar')
                ?->format('d-m-Y H:i') ?? '-';

            $userName     = $ticket->user->employee->employee_name;
            $locationName = $ticket->user->employee->store->name ?? '-';
            $phoneNumber  = $ticket->user->employee->telp_number ?? '-';
            $isOpenToProgress =
                $oldStatus === 'Open' &&
                $ticket->status === 'Progress';

            $isProgressToClosed =
                $oldStatus === 'Progress' &&
                $ticket->status === 'Closed';
            $isOverdueToProgress =
                $oldStatus === 'Overdue' &&
                $ticket->status === 'Progress';

            // =========================
            // TITLE & LINK
            // =========================
            $titleMessage = 'IT Ticket Updated';
            $ticketUrl    = $adminUrl;
            if ($isProgressToClosed) {
                $titleMessage = 'IT Ticket Closed Review';
                $ticketUrl    = $reviewUrl;
            }
            if ($isOverdueToProgress) {
                $titleMessage = 'IT Ticket Overdue to Prosses';
                $ticketUrl    = $adminUrl;
            }
            // =========================
            // MESSAGE
            // =========================
            $message =
                "{$titleMessage}\n" .
                "Date: {$formattedDate}\n" .
                "Queue: {$ticket->queue_number}\n" .
                "User: {$userName}\n" .
                "Location: {$locationName}\n" .
                "Phone: {$phoneNumber}\n" .
                "Title: {$ticket->title}\n" .
                "Category: {$ticket->category}\n" .
                "Dificulty: {$ticket->priority}\n" .
                "Executor: {$executorName}\n" .
                "Notes IT: {$ticket->notes_executor}\n" .
                "Estimation: {$estimationDate}\n" .
                "Estimation To: {$estimationToDate}\n" .
                "Finished: {$finishedDate}\n" .
                "Status: {$ticket->status}\n" .
                "Tickets Link:\n{$ticketUrl}";
            Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
                'group_id' => '120363405189832865@g.us',
                'text'     => $message,
            ]);
            Log::info('WA_UPDATE_SUCCESS', [
                'ticket_id' => $ticket->id,
                'type'      => $isProgressToClosed ? 'REVIEW' : 'UPDATE',
            ]);
        } catch (\Throwable $e) {
            Log::warning('WA_UPDATE_FAILED', [
                'error' => $e->getMessage(),
            ]);
        }
        return redirect()
            ->route('dashboard')
            ->with('success', 'Ticket successfully updated');
    }
    //   public function update(Request $request, string $hash)
    //   {
    //     $ticket = $this->findTicketByHash($hash);
    //  $oldStatus = $ticket->status;
    //     Log::info('TICKET_UPDATE_START', [
    //       'ticket_id' => $ticket->id,
    //       'user_id'   => auth()->id(),
    //       'ip'        => $request->ip(),
    //     ]);

    //     // =========================
    //     // VALIDATION
    //     // =========================
    //     $validated = $request->validate([
    //       'category'        => 'required|string',
    //       'notes_executor' => 'required|string|min:5|max:500',
    //       'priority'        => 'required|string',
    //       'finished'        => 'nullable|date',
    //       'estimation'      => 'nullable|date',
    //     ]);
    //     // =========================
    //     // STATUS SYNC (SERVER SIDE)
    //     // =========================
    //     if ($ticket->status === 'Closed') {
    //       abort(403, 'Ticket sudah closed');
    //     }

    //     if ($ticket->status === 'Open') {
    //       // TAKE TICKET
    //       $status   = 'Progress';
    //       $finished = null;
    //     } elseif ($ticket->status === 'Progress') {
    //       // CLOSE TICKET
    //       $status   = 'Closed';
    //       $finished = now();
    //     } else {
    //       abort(403, 'Status ticket tidak valid');
    //     }

    //     DB::transaction(function () use ($validated, $ticket, $status, $finished) {

    //       $estimation = !empty($validated['estimation'])
    //         ? Carbon::parse($validated['estimation'])
    //         : null;

    //       $ticket->update([
    //         'category'        => $validated['category'],
    //         'notes_executor' => $validated['notes_executor'],
    //         'status'          => $status,
    //         'priority'        => $validated['priority'],
    //         'finished'        => $finished,
    //         'estimation'      => $estimation,
    //         'executor_id'     => auth()->id(),
    //       ]);

    //       Log::info('TICKET_UPDATED', [
    //         'ticket_id' => $ticket->id,
    //         'status'    => $status,
    //       ]);
    //     });
    //     $ticket->refresh();
    // try {
    //     $hash = $this->generateTicketHash($ticket->id);

    //     $adminUrl  = route('editopenticketforadmin', $hash);
    //     $reviewUrl = route('reviewtickets', $hash);

    //     $executorName = auth()->user()->employee->employee_name
    //         ?? auth()->user()->username;

    //     $formattedDate = $ticket->created_at
    //         ?->timezone('Asia/Makassar')
    //         ?->format('d-m-Y H:i') ?? '-';

    //     $finishedDate = $ticket->finished
    //         ?->timezone('Asia/Makassar')
    //         ?->format('d-m-Y H:i') ?? '-';

    //     $estimationDate = $ticket->estimation
    //         ?->timezone('Asia/Makassar')
    //         ?->format('d-m-Y H:i') ?? '-';

    //     $userName     = $ticket->user->employee->employee_name;
    //     $locationName = $ticket->user->employee->store->name ?? '-';
    //     $phoneNumber  = $ticket->user->employee->telp_number ?? '-';

    //     // =========================
    //     // STATUS TRANSITION CHECK
    //     // =========================
    //     $isClosedFromProgress =
    //         $oldStatus === 'Progress' &&
    //         $ticket->status === 'Closed';

    //     $titleMessage = $isClosedFromProgress
    //         ? '*IT Ticket Closed Review*'
    //         : '*IT Ticket Updated*';

    //     $ticketUrl = $isClosedFromProgress
    //         ? $reviewUrl
    //         : $adminUrl;

    //     // =========================
    //     // MESSAGE
    //     // =========================
    //     $message =
    //         "{$titleMessage}\n" .
    //         "Date: {$formattedDate}\n" .
    //         "Queue: {$ticket->queue_number}\n" .
    //         "User: {$userName}\n" .
    //         "Location: {$locationName}\n" .
    //         "Phone: {$phoneNumber}\n" .
    //         "Title: {$ticket->title}\n" .
    //         "Category: {$ticket->category}\n" .
    //         "Priority: {$ticket->priority}\n" .
    //         "Executor: {$executorName}\n" .
    //         "Notes IT: {$ticket->notes_executor}\n" .
    //         "Estimation: {$estimationDate}\n" .
    //         "Finished: {$finishedDate}\n" .
    //         "Status: {$ticket->status}\n" .
    //         "Ticket Link:\n{$ticketUrl}";
    //     Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
    //         'group_id' => '120363405189832865@g.us',
    //         'text'     => $message,
    //     ]);
    //     Log::info('WA_UPDATE_SUCCESS', [
    //         'ticket_id' => $ticket->id,
    //         'type'      => $isClosedFromProgress ? 'REVIEW' : 'UPDATE',
    //     ]);
    // } catch (\Throwable $e) {
    //     Log::warning('WA_UPDATE_FAILED', [
    //         'error' => $e->getMessage(),
    //     ]);
    // }
    //     return redirect()
    //       ->route('dashboard')
    //       ->with('success', 'Ticket successfully updated');
    //   }
}

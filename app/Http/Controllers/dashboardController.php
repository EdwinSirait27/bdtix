<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Tickets;
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

    $todaysticket = Tickets::whereDate('created_at', Carbon::today())->count();
    $highprior = Tickets::where('priority', 'High')->count();
$assignedtoyou = Tickets::where('executor_id', auth()->id())->count();
$finishedtickettoyou = Tickets::whereNotNull('finished')
    ->where('executor_id', auth()->id())
    ->count();

    $onprogressticket = Tickets::where('status', 'Progress')->count();

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
    return view('pages.dashboard', compact(
        'userhuman',
        'alltickethuman',
        'overduetickethuman',
        'todaystickethuman',
        'finishedtickettoyou',
        'onprogresstickethuman',
        'user',
        'assignedtoyou',

        'todaysticket',
        'onprogressticket',
        'closedticket',
        'overdueticket',
        'slaCompliance'
    ));
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
            'created_at', 
        ])
        ->whereDate('created_at', Carbon::today()); 

    return DataTables::eloquent($query)
        ->addColumn('employee_name', function ($ticket) {
            return optional($ticket->user?->employee)->employee_name ?? '-';
        })
        ->orderColumn('employee_name', function ($query, $order) {
         
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
         <a href="' . route('showopenticket', $idHashed) . '"
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

// private function findTicketByHash(string $hash): Tickets
// {
//     $ticket = Tickets::with('user.employee')
//         ->whereRaw(
//             "SUBSTRING(SHA2(CONCAT(id, ?), 256), 1, 8) = ?",
//             [config('app.key'), $hash]
//         )
//         ->first();

//     // ❌ Jika hash tidak valid
//     abort_if(!$ticket, 404, 'Ticket tidak ditemukan');

//     // 🚫 Jika ticket Closed
//     abort_if($ticket->status === 'Closed', 403, 'Ticket sudah Closed dan tidak bisa diedit');

//     return $ticket;
// }
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

// public function edit(string $hash)
// {
//     $ticket = $this->findTicketByHash($hash);

//     // 🚫 ticket Closed → redirect dashboard
//     if ($ticket->status === 'Closed') {
//         return redirect()
//             ->route('dashboard')
//             ->with('error', 'The ticket is closed and cannot be edited.');
//     }
//     return view('pages.editopenticketforadmin', compact('ticket'));
// }
public function edit(string $hash)
{
    $ticket = $this->findTicketByHash($hash);
    $user   = auth()->user();

    // 🚫 Ticket CLOSED → tidak boleh apa pun
    if ($ticket->status === 'Closed') {
        return redirect()
            ->route('dashboard')
            ->with('error', 'The ticket is closed and cannot be edited.');
    }

    // 👨‍💼 Admin & Executor → boleh edit semua
    if ($user->hasRole(['admin', 'executor'])) {
        return view('pages.editopenticketforadmin', compact('ticket'));
    }

    // 👤 Human → hanya tiket milik sendiri
    if ($user->hasRole('human')) {

        // 🚫 BUKAN tiket dia
        if ($ticket->user_id !== $user->id) {
            abort(403, 'You are not allowed to access this ticket.');
        }

        // ✅ tiket milik sendiri → hanya view
        return redirect()->route('showopenticketforadmin', $hash);
    }

    // 🚫 Role tidak dikenal
    abort(403, 'Unauthorized action.');
}

public function show(string $hash)
{
     $ticket = $this->findTicketByHash($hash);
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

    } elseif ($ticket->status === 'Progress') {
        // CLOSE TICKET
        $status   = 'Closed';
        $finished = now();

    } else {
        abort(403, 'Status ticket tidak valid');
    }

    DB::transaction(function () use ($validated, $ticket, $status, $finished) {

        $estimation = !empty($validated['estimation'])
            ? Carbon::parse($validated['estimation'])
            : null;

        $ticket->update([
            'category'        => $validated['category'],
            'notes_executor' => $validated['notes_executor'],
            'status'          => $status,      
            'priority'        => $validated['priority'],
            'finished'        => $finished,
            'estimation'      => $estimation,
            'executor_id'     => auth()->id(),
        ]);

        Log::info('TICKET_UPDATED', [
            'ticket_id' => $ticket->id,
            'status'    => $status,
        ]);
    });

    $ticket->refresh();
    // =========================
    // WHATSAPP NOTIFICATION
    // =========================
    try {
        $hash = $this->generateTicketHash($ticket->id);
        $adminUrl = route('editopenticketforadmin', $hash);
        $executorName = auth()->user()->employee->employee_name
            ?? auth()->user()->username;
        $formattedDate = $ticket->created_at
            ->timezone('Asia/Makassar')
            ->format('d-m-Y H:i');

        $userName = $ticket->user->employee->employee_name;

        $message =
            "*Ticket Updated*\n" .
            "Queue: {$ticket->queue_number}\n" .
            "Date: {$formattedDate}\n" .
            "User: {$userName}\n" .
            "Title: {$ticket->title}\n" .
            "Category: {$ticket->category}\n" .
            "Status: {$ticket->status}\n" .
            "Priority: {$ticket->priority}\n" .
            "Executor: {$executorName}\n\n" .
            "Admin Link:\n{$adminUrl}";

        Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
            'group_id' => '120363405189832865@g.us',
            'text'     => $message,
        ]);

        Log::info('WA_UPDATE_SUCCESS', [
            'ticket_id' => $ticket->id
        ]);
    } catch (\Throwable $e) {
        Log::warning('WA_UPDATE_FAILED', [
            'error' => $e->getMessage(),
        ]);
    }

    return redirect()
        ->route('alltickets')
        ->with('success', 'Ticket successfully updated');
}

// public function update(Request $request, string $hash)
// {
//     $ticket = $this->findTicketByHash($hash);

//     Log::info('TICKET_UPDATE_START', [
//         'ticket_id' => $ticket->id,
//         'user_id'   => auth()->id(),
//         'ip'        => $request->ip(),
//     ]);

//     // =========================
//     // VALIDATION
//     // =========================
//     $validated = $request->validate([
//         'category'        => 'required|string',
//         'notes_executor' => 'required|string|min:5|max:500',
//         'status'          => 'required|string',
//         'priority'        => 'required|string',
//         'finished'        => 'nullable|date',
//         'estimation'      => 'nullable|date',
//     ]);

//     DB::transaction(function () use ($validated, $ticket) {
// $finished = !empty($validated['finished'])
//     ? Carbon::parse($validated['finished'])
//     : null;

// $estimation = !empty($validated['estimation'])
//     ? Carbon::parse($validated['estimation'])
//     : null;

//         $ticket->update([
//             'category'        => $validated['category'],
//             'notes_executor' => $validated['notes_executor'],
//             'status'          => $validated['status'],
//             'priority'        => $validated['priority'],
//             'finished'        => $finished,
//             'estimation'      => $estimation, 
//             'executor_id'     => auth()->id(),
//         ]);

//         Log::info('TICKET_UPDATED', [
//             'ticket_id' => $ticket->id
//         ]);
//     });

//     $ticket->refresh();

//     // =========================
//     // WHATSAPP NOTIFICATION
//     // =========================
//     try {
//         $hash = $this->generateTicketHash($ticket->id);

//         $adminUrl = route('editopenticketforadmin', $hash);

//         $executorName = auth()->user()->employee->employee_name
//             ?? auth()->user()->username;
//  $formattedDate = $ticket->created_at
//                     ->timezone('Asia/Makassar')
//                     ->format('d-m-Y H:i');
//                     $userName = $ticket->user->employee->employee_name;
//         $message =
//             "*Ticket Updated*\n" .
//             "Queue: {$ticket->queue_number}\n" .
//             "Date: {$formattedDate}\n" .
//             "User: {$userName}\n" .
//             "Title: {$ticket->title}\n" .
//             "Category: {$ticket->category}\n" .
//             "Status: {$ticket->status}\n" .
//             "Priority: {$ticket->priority}\n" .
//             "Executor: {$executorName}\n\n" .
//             "Admin Link:\n{$adminUrl}";

//         Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
//             'group_id' => '120363405189832865@g.us',
//             'text'     => $message,
//         ]);

//         Log::info('WA_UPDATE_SUCCESS', [
//             'ticket_id' => $ticket->id
//         ]);
//     } catch (\Throwable $e) {
//         Log::warning('WA_UPDATE_FAILED', [
//             'error' => $e->getMessage(),
//         ]);
//     }

//     return redirect()
//         ->route('alltickets')
//         ->with('success', 'Ticket successfully updated');
// }
   
// public function update(Request $request, string $hash)
// {
//     $ticket = Tickets::get()
//         ->first(function ($ticket) use ($hash) {
//             return substr(
//                 hash('sha256', $ticket->id . config('app.key')),
//                 0,
//                 8
//             ) === $hash;
//         });
//     abort_if(!$ticket, 404, 'Ticket tidak ditemukan');

//     $validated = $request->validate([
//         'title'       => 'required|string|max:255',
//         'category'    => 'required|string|max:100',
//         'description' => 'nullable|string',
//         'status'      => 'required|string',
//     ]);

//     DB::transaction(function () use ($ticket, $validated) {
//         $ticket->update([
//             'title'       => $validated['title'],
//             'category'    => $validated['category'],
//             'description' => $validated['description'],
//             'status'      => $validated['status'],
//         ]);
//     });

//     return redirect()
//         ->route('showopenticketforadmin', $hash)
//         ->with('success', 'Ticket berhasil diperbarui');
// }


}

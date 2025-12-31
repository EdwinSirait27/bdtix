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
         <a href="' . route('showopenticketforadmin', $idHashed) . '"
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
private function findTicketByHash(string $hash): Tickets
{
    $ticket = Tickets::with('user.employee')
        ->get()
        ->first(fn ($t) =>
            substr(
                hash('sha256', $t->id . config('app.key')),
                0,
                8
            ) === $hash
        );

    abort_if(!$ticket, 404, 'Ticket tidak ditemukan');

    return $ticket;
}
public function edit(string $hash)
{
    $ticket = $this->findTicketByHash($hash);
    return view('pages.editopenticketforadmin', compact('ticket'));
}
// public function update(Request $request, string $hash)
// {
//     $ticket = $this->findTicketByHash($hash);
//     $validated = $request->validate([
//         'title'       => 'required|string|max:255',
//         'category'    => 'required|string|max:100',
//         'description' => 'nullable|string',
//         'status'      => 'required|string',
//         'updated_at'  => 'required|date',
//     ]);

//     $affected = Tickets::where('id', $ticket->id)
//         ->where('updated_at', $validated['updated_at'])
//         ->update([
//             'title'       => $validated['title'],
//             'category'    => $validated['category'],
//             'description' => $validated['description'],
//             'status'      => $validated['status'],
//             'updated_at'  => now(),
//         ]);

//     if ($affected === 0) {
//         return back()
//             ->withErrors([
//                 'conflict' =>
//                     'Ticket ini sudah diperbarui oleh admin lain. '
//                     . 'Silakan reload halaman untuk melihat perubahan terbaru.'
//             ])
//             ->withInput();
//     }

//     return redirect()
//         ->route('showopenticketforadmin', $hash)
//         ->with('success', 'Ticket berhasil diperbarui');
// }
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
//     try {
//         $validated = $request->validate([
//             'category'      => 'required|string',
//             'notes_executor'   => 'required|string|min:5|max:500',
//             'status'        => 'required|string',
//             'priority'        => 'required|string',
//             'finished'        => 'nullable',
//             'estimation'        => 'nullable',
//         ]);

//         Log::info('UPDATE_VALIDATION_SUCCESS', $validated);
//     } catch (\Throwable $e) {
//         Log::error('UPDATE_VALIDATION_FAILED', [
//             'error' => $e->getMessage(),
//         ]);
//         throw $e;
//     }

//     try {
//         DB::transaction(function () use ($request, $validated, $ticket) {

//             Log::info('DB_TRANSACTION_UPDATE_START', [
//                 'ticket_id' => $ticket->id
//             ]);
//             // =========================
//             // UPDATE TICKET
//             // =========================
//             $ticket->update([
//                 'title'       => $validated['title'],
//                 'category'    => $validated['category'],
//                 'description' => $validated['description'],
//                 'status'      => $validated['status'],
//                 'priority'      => $validated['priority'],
//                 'finished'      => $validated['finished'],
//                 'estrimation'      => $validated['estrimation'],
//                 'executor_id'      => auth()->id(),
//             ]);
//             Log::info('TICKET_UPDATED', [
//                 'ticket_id' => $ticket->id
//             ]);
//             // =========================
//             // ATTACHMENT PROCESS (OPTIONAL)
//             // =========================
//             if ($request->hasFile('attachments')) {

//                 $categoryFolder = Str::slug($ticket->category);
//                 $userFolder     = Str::slug($ticket->user->username);
//                 $ticketFolder   = $ticket->id;

//                 $basePath = "ticket/{$categoryFolder}/{$userFolder}/{$ticketFolder}";

//                 try {
//                     NextcloudService::makeDir('ticket');
//                     NextcloudService::makeDir("ticket/{$categoryFolder}");
//                     NextcloudService::makeDir("ticket/{$categoryFolder}/{$userFolder}");
//                     NextcloudService::makeDir($basePath);
//                 } catch (\Throwable $e) {
//                     Log::error('NEXTCLOUD_MKDIR_FAILED', [
//                         'path'  => $basePath,
//                         'error' => $e->getMessage()
//                     ]);
//                     throw $e;
//                 }

//                 foreach ($request->file('attachments') as $file) {
//                     $filename = time() . '_' . $file->getClientOriginalName();

//                     try {
//                         NextcloudService::upload(
//                             $basePath,
//                             $filename,
//                             file_get_contents($file->getRealPath()),
//                             $file->getMimeType()
//                         );

//                         Ticketattachments::create([
//                             'id'        => (string) Str::uuid(),
//                             'ticket_id' => $ticket->id,
//                             'file_name' => $filename,
//                             'file_path' => "{$basePath}/{$filename}",
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
//                 // SHARE FOLDER (ONLY IF EMPTY)
//                 // =========================
               
//             }

//             Log::info('DB_TRANSACTION_UPDATE_END');
//         });

//         $ticket->refresh();

//         // =========================
//         // WHATSAPP NOTIFICATION (UPDATE)
//         // =========================
//         try {
//             $message =
//                 "*Ticket Updated*\n" .
//                 "Queue: {$ticket->queue_number}\n" .
//                 "Title: {$ticket->title}\n" .
//                 "Category: {$ticket->category}\n" .
//                 "Status: {$ticket->status}\n" .
//                 "Updated by: {$ticket->executor_id};

            

//             Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
//                 'group_id' => '120363405189832865@g.us',
//                 'text'     => $message,
//             ]);
//         } catch (\Throwable $e) {
//             Log::warning('WA_UPDATE_FAILED', [
//                 'error' => $e->getMessage(),
//             ]);
//         }

//         Log::info('TICKET_UPDATE_SUCCESS', [
//             'ticket_id' => $ticket->id
//         ]);

//         return redirect()
//             ->route('alltickets')
//             ->with('success', 'Ticket successfully updated');

//     } catch (\Throwable $e) {

//         Log::critical('TICKET_UPDATE_FAILED', [
//             'ticket_id' => $ticket->id,
//             'error'     => $e->getMessage(),
//             'trace'     => $e->getTraceAsString(),
//         ]);

//         return redirect()
//             ->back()
//             ->withInput()
//             ->with('error', 'Ticket failed to update');
//     }
// }
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
        'status'          => 'required|string',
        'priority'        => 'required|string',
        'finished'        => 'nullable|boolean',
        'estimation'      => 'nullable|date',
    ]);

    DB::transaction(function () use ($validated, $ticket) {

        $ticket->update([
            'category'        => $validated['category'],
            'notes_executor' => $validated['notes_executor'],
            'status'          => $validated['status'],
            'priority'        => $validated['priority'],
            'finished'        => $validated['finished'] ?? false,
            'estimation'      => $validated['estimation'] ?? null,
            'executor_id'     => auth()->id(),
        ]);

        Log::info('TICKET_UPDATED', [
            'ticket_id' => $ticket->id
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

        $message =
            "*Ticket Updated*\n" .
            "Queue: {$ticket->queue_number}\n" .
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




   public function show(string $hash)
{
    $ticket = Tickets::with(['user.employee'])
        ->get()
        ->first(function ($ticket) use ($hash) {
            return substr(
                hash('sha256', $ticket->id . config('app.key')),
                0,
                8
            ) === $hash;
        });

    abort_if(!$ticket, 404, 'Ticket tidak ditemukan');

    return view('pages.showopenticketforadmin', [
        'ticket' => $ticket,
    ]);
}
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

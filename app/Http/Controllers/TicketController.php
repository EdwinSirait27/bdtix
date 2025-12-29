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

class TicketController extends Controller
{
    public function openTicket()
    {
        return view('pages.openticket');
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
        $onprogressticket = Tickets::where('status', 'Progress')
            ->count();
        return view('pages.alltickets', compact('todaysticket', 'onprogressticket'));
    }
    //        public function getAlltickets(Request $request)
    // {
    //     $query = Tickets::select([
    //             'id',
    //             'user_id',
    //             'queue_number',
    //             'title',
    //             'category',
    //             'status',
    //     ])->with('user.employee');


    //     return DataTables::eloquent($query)
    //         ->addColumn('employee_name', function ($tickets) {
    //             return optional($tickets->user->employee)->employee_name ?? 'Empty';
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
    public function getAllmytickets(Request $request)
    {
        $query = Tickets::with('user.employee')
            ->where('user_id', auth()->id())
            ->select([
                'id',
                'queue_number',
                'title',
                'category',
                'status',
            ]);
        return DataTables::eloquent($query)
            ->addColumn('employee_name', function ($ticket) {
                return optional($ticket->user?->employee)->employee_name ?? '-';
            })
            ->orderColumn('employee_name', function ($query, $order) {})
            ->addColumn('action', function ($user) {
                $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
                return '
        <a href="' . route('editmytickets', $idHashed) . '"
           class="inline-flex items-center justify-center p-2 
                  text-slate-500 hover:text-indigo-600 
                  hover:bg-indigo-50 rounded-full transition"
           title="Edit Tickets: ' . e($user->title) . '">

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
         <a href="' . route('showmytickets', $idHashed) . '"
           class="inline-flex items-center justify-center p-2
                  text-slate-500 hover:text-emerald-600
                  hover:bg-emerald-50 rounded-full transition"
           title="Show Tickets: ' . e($user->title) . '">

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
    public function show($hash)
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

        return view('pages.showmytickets', compact('ticket'));
    }
    public function edit($hash)
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

        return view('pages.editmytickets', compact('ticket'));
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
        $query = Tickets::with('user.employee')
            ->select([
                'id',
                'user_id',
                'queue_number',
                'title',
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
            ->addColumn('action', function ($user) {
                $idHashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);

                return '
        <a href="' . route('editalltickets', $idHashed) . '"
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
         <a href="' . route('showalltickets', $idHashed) . '"
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
        Log::info('TICKET_STORE_START', [
            'user_id' => auth()->id(),
            'ip'      => $request->ip(),
            'has_file' => $request->hasFile('attachments'),
            'file_count' => is_array($request->file('attachments'))
                ? count($request->file('attachments'))
                : 0,
        ]);

        // =========================
        // VALIDATION
        // =========================
        try {
            $validated = $request->validate([
                'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
                'title'         => 'required|string|min:5|max:150',
                'category'      => 'required|string',
                'description'   => 'required|string|min:5|max:500',
                'attachments'   => 'nullable|array|min:1|max:3',
                'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
            ]);

            Log::info('VALIDATION_SUCCESS', $validated);
        } catch (\Throwable $e) {
            Log::error('VALIDATION_FAILED', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        try {
            DB::transaction(function () use ($request, $validated, &$ticket) {

                Log::info('DB_TRANSACTION_START');

                // =========================
                // QUEUE NUMBER
                // =========================
                $queueNumber = Tickets::whereDate('created_at', Carbon::today())
                    ->lockForUpdate()
                    ->count() + 1;

                Log::info('QUEUE_NUMBER_GENERATED', [
                    'queue_number' => $queueNumber
                ]);

                // =========================
                // CREATE TICKET
                // =========================
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

                Log::info('TICKET_CREATED', [
                    'ticket_id' => $ticket->id
                ]);

                // =========================
                // ATTACHMENT PROCESS
                // =========================
                Log::info('ATTACHMENT_CHECK', [
                    'has_file' => $request->hasFile('attachments')
                ]);

                if ($request->hasFile('attachments')) {

                    $categoryFolder = Str::slug($ticket->category);
                    $userFolder     = Str::slug(auth()->user()->username);
                    $ticketFolder   = $ticket->id;

                    $basePath = "ticket/{$categoryFolder}/{$userFolder}/{$ticketFolder}";

                    Log::info('NEXTCLOUD_PATH_PREPARED', [
                        'base_path' => $basePath
                    ]);

                    try {
                        NextcloudService::makeDir('ticket');
                        NextcloudService::makeDir("ticket/{$categoryFolder}");
                        NextcloudService::makeDir("ticket/{$categoryFolder}/{$userFolder}");
                        NextcloudService::makeDir($basePath);

                        Log::info('NEXTCLOUD_DIRECTORIES_CREATED');
                    } catch (\Throwable $e) {
                        Log::error('NEXTCLOUD_MKDIR_FAILED', [
                            'error' => $e->getMessage(),
                            'path'  => $basePath
                        ]);
                        throw $e;
                    }

                    foreach ($request->file('attachments') as $index => $file) {
                        $filename = time() . '_' . $file->getClientOriginalName();

                        Log::info('UPLOAD_FILE_START', [
                            'index'    => $index,
                            'filename' => $filename,
                            'mime'     => $file->getMimeType(),
                            'size'     => $file->getSize(),
                        ]);
                        try {
                            NextcloudService::upload(
                                $basePath,
                                $filename,
                                file_get_contents($file->getRealPath()),
                                $file->getMimeType()
                            );

                            Log::info('UPLOAD_FILE_SUCCESS', [
                                'filename' => $filename
                            ]);

                            Ticketattachments::create([
                                'id'        => (string) Str::uuid(),
                                'ticket_id' => $ticket->id,
                                'file_name' => $filename,
                                'file_path' => "{$basePath}/{$filename}",
                            ]);

                            Log::info('ATTACHMENT_DB_CREATED', [
                                'filename' => $filename
                            ]);
                        } catch (\Throwable $e) {
                            Log::error('UPLOAD_FILE_FAILED', [
                                'filename' => $filename,
                                'error'    => $e->getMessage()
                            ]);
                            throw $e;
                        }
                    }

                    // =========================
                    // SHARE FOLDER
                    // =========================
                    try {
                        $shareUrl = NextcloudService::shareFolder($basePath);

                        Log::info('NEXTCLOUD_SHARE_SUCCESS', [
                            'url' => $shareUrl
                        ]);

                        $ticket->update([
                            'attachment_folder' => $basePath,
                            'attachment_url'    => $shareUrl,
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('NEXTCLOUD_SHARE_FAILED', [
                            'path'  => $basePath,
                            'error' => $e->getMessage(),
                        ]);
                        throw $e;
                    }
                }

                Log::info('DB_TRANSACTION_END');
            });

            $ticket->refresh();
            Log::info('TICKET_REFRESHED', ['ticket_id' => $ticket->id]);

            // =========================
            // WHATSAPP NOTIFICATION
            // =========================
            try {
                Log::info('WA_SEND_START', [
                    'ticket_id' => $ticket->id
                ]);
                $formattedDate = $ticket->created_at
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i');

                $userName = auth()->user()->employee->employee_name
                    ?? auth()->user()->employee->store->name
                    ?? auth()->user()->username;

                $message =
                    "*New Ticket*\n" .
                    "Queue: {$ticket->queue_number}\n" .
                    "Date: {$formattedDate}\n" .
                    "Title: {$ticket->title}\n" .
                    "Category: {$ticket->category}\n" .
                    "Description: {$ticket->description}\n" .
                    "User: {$userName}";

                if (!empty($ticket->attachment_url)) {
                    $message .= "\nAttachments:\n{$ticket->attachment_url}";
                }

                Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
                    'group_id' => '120363405189832865@g.us',
                    'text'     => $message,
                ]);


                Log::info('WA_SEND_SUCCESS');
            } catch (\Throwable $e) {
                Log::warning('WA_SEND_FAILED', [
                    'error' => $e->getMessage(),
                ]);
            }
            Log::info('TICKET_STORE_SUCCESS', [
                'ticket_id' => $ticket->id
            ]);

            return redirect()->route('openticket')
                ->with('success', 'Ticket successfully submitted');
        } catch (\Throwable $e) {
            Log::critical('TICKET_STORE_FAILED', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('openticket')
                ->with('error', 'Ticket failed to submitted');
        }
    }

    // public function store(Request $request)
    // {
    //     Log::info('Ticket store request masuk', [
    //         'user_id' => auth()->id(),
    //         'ip'      => $request->ip(),
    //     ]);

    //     $validated = $request->validate([
    //         'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
    //         'title'         => 'required|string|min:5|max:150',
    //         'category'      => 'required|string',
    //         'description'   => 'required|string|min:5|max:500',
    //         'attachments'   => 'nullable|array|min:1|max:3',
    //         'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($request, $validated, &$ticket) {

    //             $queueNumber = Tickets::whereDate('created_at', Carbon::today())
    //                 ->lockForUpdate()
    //                 ->count() + 1;

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

    //             // ==============================
    //             // 📎 NEXTCLOUD ATTACHMENTS
    //             // ==============================
    //             Log::info('HAS FILE CHECK', [
    //     'hasFile' => $request->hasFile('attachments'),
    //     'files'   => $request->file('attachments'),
    // ]);

    //             if ($request->hasFile('attachments')) {

    //                 $categoryFolder = Str::slug($ticket->category);
    //                 $userFolder     = Str::slug(auth()->user()->username);
    //                 $ticketFolder   = $ticket->id;

    //                 $basePath = "ticket/{$categoryFolder}/{$userFolder}/{$ticketFolder}";

    //                 // pastikan folder ada
    //                 NextcloudService::makeDir('ticket');
    //                 NextcloudService::makeDir("ticket/{$categoryFolder}");
    //                 NextcloudService::makeDir("ticket/{$categoryFolder}/{$userFolder}");
    //                 NextcloudService::makeDir($basePath);

    //                 foreach ($request->file('attachments') as $file) {
    //                     $filename = time() . '_' . $file->getClientOriginalName();

    //                     NextcloudService::upload(
    //                         $basePath,
    //                         $filename,
    //                         file_get_contents($file->getRealPath()),
    //                         $file->getMimeType()
    //                     );

    //                     Ticketattachments::create([
    //                         'id'        => (string) Str::uuid(),
    //                         'ticket_id' => $ticket->id,
    //                         'file_name' => $filename,
    //                         'file_path' => "{$basePath}/{$filename}",
    //                     ]);
    //                 }

    //                 // 🔗 SHARE FOLDER (INI YANG PENTING)
    //                 $shareUrl = NextcloudService::shareFolder($basePath);
    //                 Log::info('NEXTCLOUD SHARE CREATED', [
    //     'url' => $shareUrl,
    // ]);


    //                 $ticket->update([
    //                     'attachment_folder' => $basePath,
    //                     'attachment_url'    => $shareUrl,
    //                 ]);
    //             }
    //         });
    //         $ticket->refresh();

    //         // ==============================
    //         // 📲 WHATSAPP NOTIFICATION
    //         // ==============================
    //         $attachmentText = '';
    //         if (!empty($ticket->attachment_url)) {
    //             $attachmentText =
    //                 "\n Attachments:\n" .
    //                 $ticket->attachment_url;
    //         }

    //         try {
    //             Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
    //                 'group_id' => '120363405189832865@g.us',
    //                 'text' =>
    //                     "*New Ticket*\n" .
    //                     "Queue: {$ticket->queue_number}\n" .
    //                     "Date: " . $ticket->created_at
    //                         ->timezone('Asia/Makassar')
    //                         ->format('d-m-Y H:i') . "\n" .
    //                     "Title: {$ticket->title}\n" .
    //                     "Category: {$ticket->category}\n" .
    //                     "Description: {$ticket->description}\n" .
    //                     "User: " . (
    //                         auth()->user()->employee->employee_name
    //                         ?? auth()->user()->employee->store->name
    //                     ) .
    //                     $attachmentText,
    //             ]);
    //             Log::info('WA MESSAGE FINAL', [
    //     'attachment_url' => $ticket->attachment_url
    // ]);

    //         } catch (\Throwable $e) {
    //             Log::warning('Gagal kirim WA notification', [
    //                 'error' => $e->getMessage(),
    //             ]);
    //         }

    //         return redirect()
    //             ->route('openticket')
    //             ->with('success', 'Ticket successfully submitted');

    //     } catch (\Throwable $e) {
    //         Log::error('failed to submitted ticket', [
    //             'message' => $e->getMessage(),
    //             'user_id' => auth()->id(),
    //         ]);

    //         return redirect()
    //             ->route('openticket')
    //             ->with('error', 'Ticket failed to submitted');
    //     }
    // }


    // public function store(Request $request)
    // {
    //     Log::info('TICKET REQUEST START', [
    //         'user_id'        => auth()->id(),
    //         'ip'             => $request->ip(),
    //         'content_length' => $request->server('CONTENT_LENGTH'),
    //     ]);

    //     // 🛑 Guard khusus Swoole
    //     if (
    //         $request->isMethod('post') &&
    //         is_null($request->server('CONTENT_LENGTH'))
    //     ) {
    //         Log::error('EMPTY REQUEST BODY - SWOOLE GUARD');
    //         return back()->withErrors([
    //             'attachments' => 'Upload gagal, silakan ulangi',
    //         ]);
    //     }

    //     $validated = $request->validate([
    //         'request_uuid'  => 'required|uuid|unique:ticket_tables,request_uuid',
    //         'title'         => 'required|string|min:5|max:150',
    //         'category'      => 'required|string',
    //         'description'   => 'required|string|min:5|max:500',
    //         'attachments'   => 'nullable|array|min:1|max:3',
    //         'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    //     ]);

    //     $ticket = null;

    //     try {
    //         // ==============================
    //         // 🎫 DB TRANSACTION (ONLY DB)
    //         // ==============================
    //         DB::beginTransaction();
    //         Log::info('DB TRANSACTION START');

    //         $queueNumber = Tickets::whereDate('created_at', Carbon::today())
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

    //         Log::info('TICKET CREATED', [
    //             'ticket_id' => $ticket->id,
    //             'queue'     => $queueNumber,
    //         ]);

    //         DB::commit();
    //         Log::info('DB TRANSACTION COMMIT', [
    //             'ticket_id' => $ticket->id,
    //         ]);

    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         Log::error('TICKET STORE FAILED', [
    //             'error'   => $e->getMessage(),
    //             'trace'   => $e->getTraceAsString(),
    //             'user_id' => auth()->id(),
    //         ]);

    //         return redirect()
    //             ->route('openticket')
    //             ->with('error', 'Ticket failed to submitted');
    //     }

    //     // ==============================
    //     // 📎 ATTACHMENTS (OUTSIDE DB)
    //     // ==============================
    //     $this->handleAttachments($request, $ticket);

    //     // ==============================
    //     // 📲 WHATSAPP
    //     // ==============================
    //     $this->sendWhatsappNotification($ticket);

    //     return redirect()
    //         ->route('openticket')
    //         ->with('success', 'Ticket successfully submitted');
    // }

    // private function handleAttachments(Request $request, Tickets $ticket): void
    // {
    //     if (! $request->hasFile('attachments')) {
    //         Log::info('NO ATTACHMENTS UPLOADED');
    //         return;
    //     }

    //     $files = $request->file('attachments');

    //     Log::info('ATTACHMENT START', [
    //         'count'      => count($files),
    //         'total_size' => collect($files)->sum(fn ($f) => $f->getSize()),
    //     ]);

    //     $categoryFolder = Str::slug($ticket->category);
    //     $userFolder     = Str::slug(auth()->user()->username);
    //     $basePath       = "ticket/{$categoryFolder}/{$userFolder}/{$ticket->id}";

    //     foreach ([
    //         'ticket',
    //         "ticket/{$categoryFolder}",
    //         "ticket/{$categoryFolder}/{$userFolder}",
    //         $basePath,
    //     ] as $dir) {
    //         NextcloudService::makeDir($dir);
    //     }

    //     foreach ($files as $index => $file) {

    //         Log::info('UPLOADING FILE', [
    //             'index' => $index,
    //             'name'  => $file->getClientOriginalName(),
    //             'size'  => $file->getSize(),
    //             'mime'  => $file->getMimeType(),
    //         ]);

    //         $filename = time() . '_' . $file->getClientOriginalName();

    //         // ✅ SWOOLE SAFE FLOW
    //         $tmpPath = $file->store('tmp-uploads');
    //         $stream  = Storage::readStream($tmpPath);

    //         NextcloudService::upload(
    //             $basePath,
    //             $filename,
    //             $stream,
    //             $file->getMimeType()
    //         );

    //         if (is_resource($stream)) {
    //             fclose($stream);
    //         }

    //         Storage::delete($tmpPath);

    //         Ticketattachments::create([
    //             'id'        => (string) Str::uuid(),
    //             'ticket_id' => $ticket->id,
    //             'file_name' => $filename,
    //             'file_path' => "{$basePath}/{$filename}",
    //         ]);
    //     }

    //     $shareUrl = NextcloudService::shareFolder($basePath);

    //     Log::info('NEXTCLOUD SHARE CREATED', [
    //         'url' => $shareUrl,
    //     ]);

    //     $ticket->update([
    //         'attachment_folder' => $basePath,
    //         'attachment_url'    => $shareUrl,
    //     ]);
    // }

    // private function sendWhatsappNotification(Tickets $ticket): void
    // {
    //     try {
    //         $attachmentText = $ticket->attachment_url
    //             ? "\nAttachments:\n{$ticket->attachment_url}"
    //             : '';

    //         $response = Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
    //             'group_id' => '120363405189832865@g.us',
    //             'text' =>
    //                 "*New Ticket*\n" .
    //                 "Queue: {$ticket->queue_number}\n" .
    //                 "Date: " . $ticket->created_at
    //                     ->timezone('Asia/Makassar')
    //                     ->format('d-m-Y H:i') . "\n" .
    //                 "Title: {$ticket->title}\n" .
    //                 "Category: {$ticket->category}\n" .
    //                 "Description: {$ticket->description}\n" .
    //                 "User: " . (
    //                     auth()->user()->employee->employee_name
    //                     ?? auth()->user()->employee->store->name
    //                 ) .
    //                 $attachmentText,
    //         ]);

    //         Log::info('WA SENT', [
    //             'status' => $response->status(),
    //             'body'   => $response->body(),
    //         ]);

    //     } catch (\Throwable $e) {
    //         Log::warning('WA FAILED', [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

}

// public function store(Request $request)
// {
//     Log::info('Ticket store request masuk', [
//         'user_id' => auth()->id(),
//         'ip'      => $request->ip(),
//     ]);

//     $validated = $request->validate([
//         'request_uuid' => 'required|uuid|unique:ticket_tables,request_uuid',
//         'title'        => 'required|string|min:5|max:150',
//         'category'     => 'required|string',
//         'description'  => 'required|string|min:5|max:500',
//         'attachments'  => 'nullable|array|min:1|max:3',
//         'attachments.*'=> 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
//     ]);

//     try {
//         DB::transaction(function () use ($request, $validated, &$ticket) {

//             // 🔢 Hitung antrian HARI INI (AMAN)
//             $queueNumber = Tickets::whereDate('created_at', Carbon::today())
//                 ->lockForUpdate()
//                 ->count() + 1;

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

//             // if ($request->hasFile('attachments')) {
//             //     foreach ($request->file('attachments') as $file) {
//             //         $path = $file->store("tickets/{$ticket->id}", 'public');

//             //         Ticketattachments::create([
//             //             'id'        => (string) Str::uuid(),
//             //             'ticket_id'=> $ticket->id,
//             //             'file_name'=> $file->getClientOriginalName(),
//             //             'file_path'=> $path,
//             //         ]);
//             //     }
//             // }
//             if ($request->hasFile('attachments')) {

//     $categoryFolder = Str::slug($ticket->category);
//     $basePath = "ticket/{$categoryFolder}/{$ticket->id}";

//     // 📁 Pastikan folder ada
//     NextcloudService::makeDir("ticket");
//     NextcloudService::makeDir("ticket/{$categoryFolder}");
//     NextcloudService::makeDir($basePath);

//     foreach ($request->file('attachments') as $file) {

//         $filename = time() . '_' . $file->getClientOriginalName();

//         NextcloudService::upload(
//             $basePath,
//             $filename,
//             file_get_contents($file->getRealPath()),
//             $file->getMimeType()
//         );

//         Ticketattachments::create([
//             'id'        => (string) Str::uuid(),
//             'ticket_id' => $ticket->id,
//             'file_name' => $filename,
//             'file_path' => "{$basePath}/{$filename}",
//         ]);
//     }
// }

//         });
//         // 🔔 Kirim WA (DI LUAR TRANSACTION)
//         try {
//             Http::timeout(5)->post('http://127.0.0.1:3000/send-message', [
//                 'group_id' => '120363405189832865@g.us',
//                 'text' =>
//                     "*New Ticket*\n" .
//                     "Queue: {$ticket->queue_number}\n" .
//                     "Date: " . $ticket->created_at
//                         ->timezone('Asia/Jakarta')
//                         ->format('d-m-Y H:i') . "\n" .
//                     "Title: {$ticket->title}\n" .
//                     "Category: {$ticket->category}\n" .
//                     "Description: {$ticket->description}\n" .
//                     "User: " . (
//                         auth()->user()->employee->employee_name
//                         ?? auth()->user()->employee->store->name
//                         ) . 
//                         "Attachment: $attachmentText"
                    
            
//             ]);
//         } catch (\Throwable $e) {
//             Log::warning('Gagal kirim WA notification', [
//                 'error' => $e->getMessage(),
//             ]);
//         }

//         return redirect()
//             ->route('openticket')
//             ->with('success', 'Ticket successfully submitted');

//     } catch (\Throwable $e) {
//         Log::error('failed to submitted ticket', [
//             'message' => $e->getMessage(),
//             'user_id' => auth()->id(),
//         ]);

//         return redirect()
//             ->route('openticket')
//             ->with('error', 'Ticket failed to submitted');
//     }
// }
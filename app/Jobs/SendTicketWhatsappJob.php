<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
// public function handle()
// {
//     Log::info('WA_JOB_START', [
//         'ticket_id' => $this->ticketId
//     ]);
//     try {
//         $ticket = Tickets::with('user.employee.store')
//             ->findOrFail($this->ticketId);
//                $hash = substr(
//                 hash('sha256', $ticket->id . config('app.key')),
//                 0,
//                 8
//             );
//     $editTicketUrl = route('editopenticketforadmin', $hash);

//         $createdAt = $ticket->created_at
//             ->timezone('Asia/Makassar')
//             ->format('d-m-Y H:i');

//         $user = $ticket->user;
//         $employee = $user?->employee;
//         $store = $employee?->store;

//         $userName =
//             $employee->employee_name
//             ?? $store->name
//             ?? $user->username;

//         $locationName = $store->name ?? '-';
//         $phoneNumber  = $employee->telp_number ?? '-';

//         $message = "*New IT Ticket*\n"
//             ."Queue: {$ticket->queue_number}\n"
//             ."Date: {$createdAt}\n"
//             ."User: {$userName}\n"
//             ."Location: {$locationName}\n"
//             ."Phone Number: {$phoneNumber}\n"
//             ."Title: {$ticket->title}\n"
//             ."Category: {$ticket->category}\n"
//             ."Description: {$ticket->description}\n"
//             ."*Tickets Url Links*\n" 
//             ."{$editTicketUrl}";
//         if ($ticket->attachment_url) {
//             $message .= "\nAttachments:\n{$ticket->attachment_url}";
//         }
//         $response = Http::timeout(10)->post(
//             'http://127.0.0.1:3000/send-message',
//             [
//                 'group_id' => '120363405189832865@g.us',
//                 'text'     => $message,
//             ]
//         );
//         Log::info('WA_JOB_RESPONSE', [
//             'status' => $response->status(),
//             'body'   => $response->body(),
//         ]);

//         if (! $response->successful()) {
//             throw new \Exception(
//                 'WA API failed: ' . $response->body()
//             );
//         }

//     } catch (\Throwable $e) {
//         Log::error('WA_JOB_FAILED', [
//             'ticket_id' => $this->ticketId,
//             'error' => $e->getMessage(),
//         ]);

//         // throw $e;
//         return;
//     }
// }
class SendTicketWhatsappJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $timeout = 30;

    public function __construct(public string $ticketId) {}


// public function handle(): void
// {
//     Log::info('WA_JOB_START', [
//         'ticket_id' => $this->ticketId
//     ]);

//     try {
//         $ticket = Tickets::with('user.employee.store')
//             ->find($this->ticketId);

//         // ❌ kalau ticket tidak ada → stop
//         if (! $ticket) {
//             Log::warning('WA_JOB_TICKET_NOT_FOUND', [
//                 'ticket_id' => $this->ticketId
//             ]);
//             return;
//         }

//         // ✅ generate hash AMAN
//         $hash = substr(
//             hash('sha256', $ticket->id . config('app.key')),
//             0,
//             8
//         );

//         // ✅ generate url TANPA helper route (lebih aman di job)
//         $editTicketUrl = config('app.url')
//             . '/editopenticketforadmin/' . $hash;

//         $createdAt = $ticket->created_at
//             ->timezone('Asia/Makassar')
//             ->format('d-m-Y H:i');

//         $user     = $ticket->user;
//         $employee = $user?->employee;
//         $store    = $employee?->store;

//         $userName =
//             $employee->employee_name           
//             ?? $user->username;

//         $locationName = $store->name ?? '-';
//         $phoneNumber  = $employee->telp_number ?? '-';

//         $message =
//             "*New IT Ticket*\n" .
//             "Queue: {$ticket->queue_number}\n" .
//             "Date: {$createdAt}\n" .
//             "User: {$userName}\n" .
//             "Location: {$locationName}\n" .
//             "Phone Number: {$phoneNumber}\n" .
//             "Title: {$ticket->title}\n" .
//             "Category: {$ticket->category}\n" .
//             "Description: {$ticket->description}\n" .
//             "*Ticket Link*\n" .
//             "{$editTicketUrl}";

//         // if ($ticket->attachment_url) {
//         //     $message .= "\nAttachments:\n{$ticket->attachment_url}";
//         // }

//         $response = Http::timeout(10)->post(
//             'http://127.0.0.1:3000/send-message',
//             [
//                 'group_id' => '120363405189832865@g.us',
//                 'text'     => $message,
//             ]
//         );

//         Log::info('WA_JOB_RESPONSE', [
//             'ticket_id' => $ticket->id,
//             'status'    => $response->status(),
//             'body'      => $response->body(),
//         ]);

//     } catch (\Throwable $e) {

//         // ❗ WA GAGAL TIDAK BOLEH BLOCK
//         Log::error('WA_JOB_FAILED', [
//             'ticket_id' => $this->ticketId,
//             'error'     => $e->getMessage(),
//         ]);

//         return;
//     }
// }
public function handle(): void
{
    Log::info('WA_JOB_START', [
        'ticket_id' => $this->ticketId
    ]);

    try {
        $ticket = Tickets::find($this->ticketId);

        if (! $ticket) {
            Log::warning('WA_JOB_TICKET_NOT_FOUND', [
                'ticket_id' => $this->ticketId
            ]);
            return;
        }

        // 🔐 HASH AMAN
        $hash = substr(
            hash('sha256', $ticket->id . config('app.key')),
            0,
            8
        );

        $editTicketUrl = config('app.url')
            . '/editopenticketforadmin/' . $hash;

        // 🔄 AMBIL DATA MINIMAL (AMAN)
        $user = User::with('employee.store')->find($ticket->user_id);

        $employee = $user?->employee;
        $store    = $employee?->store;

        $createdAt = optional($ticket->created_at)
            ->timezone('Asia/Makassar')
            ->format('d-m-Y H:i') ?? '-';

        $userName = $employee->employee_name
            ?? $store->name
            ?? $user?->username
            ?? 'Unknown';

        $locationName = $store->name ?? '-';
        $phoneNumber  = $employee->telp_number ?? '-';

        // 🧱 MESSAGE TIDAK BOLEH KOSONG
        $message = implode("\n", [
            "*New IT Ticket*",
            "Queue: {$ticket->queue_number}",
            "Date: {$createdAt}",
            "User: {$userName}",
            "Location: {$locationName}",
            "Phone: {$phoneNumber}",
            "Title: {$ticket->title}",
            "Category: {$ticket->category}",
            "Description: {$ticket->description}",
            "*Ticket Link*",
            $editTicketUrl,
        ]);

        // OPTIONAL attachment
        if (!empty($ticket->attachment_url)) {
            $message .= "\nAttachments:\n{$ticket->attachment_url}";
        }

        // 🚀 KIRIM WA
        $response = Http::timeout(10)->post(
            'http://127.0.0.1:3000/send-message',
            [
                'group_id' => '120363405189832865@g.us',
                'text'     => $message,
            ]
        );

        Log::info('WA_JOB_SENT', [
            'ticket_id' => $ticket->id,
            'status'    => $response->status(),
        ]);

    } catch (\Throwable $e) {

        // ❗ WA GAGAL TIDAK BOLEH GAGALKAN TICKET
        Log::error('WA_JOB_FAILED', [
            'ticket_id' => $this->ticketId,
            'error'     => $e->getMessage(),
        ]);
    }
}


}

<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Tickets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
class SendTicketWhatsappJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $timeout = 30;

    public function __construct(public string $ticketId) {}

//     public function handle()
//     {
//         $ticket = Tickets::with('user.employee.store')->findOrFail($this->ticketId);
// $createdAt = $ticket->created_at
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

//         $message = "*New Tickets*\n"
//         ."Queue: {$ticket->queue_number}\n"
//         ."Date: {$createdAt}\n"
//         ."User: {$userName}\n"
//         ."Location: {$locationName}\n"
//         ."Phone Number: {$phoneNumber}\n"
//         ."Title: {$ticket->title}\n"
//         ."Category: {$ticket->category}\n"
//         ."Description: {$ticket->description}";
//         if ($ticket->attachment_url) {
//             $message .= "\nAttachments:\n{$ticket->attachment_url}";
//         }
//         Http::timeout(15)->post('http://127.0.0.1:3000/send-message', [
//             'group_id' => '120363405189832865@g.us',
//             'text'     => $message,
//         ]);
//     }
// public function handle()
// {
//     Log::info('WA_JOB_START', [
//         'ticket_id' => $this->ticketId
//     ]);

//     $ticket = Tickets::with('user.employee.store')->findOrFail($this->ticketId);
// $createdAt = $ticket->created_at
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

//     $message = "*New IT Ticket*\n"
//         ."Queue: {$ticket->queue_number}\n"
//         ."Date: {$createdAt}\n"
//         ."User: {$userName}\n"
//         ."Location: {$locationName}\n"
//         ."Phone Number: {$phoneNumber}\n"
//         ."Title: {$ticket->title}\n"
//         ."Category: {$ticket->category}\n"
//         ."Description: {$ticket->description}";

//     if ($ticket->attachment_url) {
//         $message .= "\nAttachments:\n{$ticket->attachment_url}";
//     }

//     $response = Http::timeout(10)->post(
//         'http://127.0.0.1:3000/send-message',
//         [
//             'group_id' => '120363405189832865@g.us',
//             'text'     => $message,
//         ]
//     );
//     Log::info('WA_JOB_RESPONSE', [
//         'status' => $response->status(),
//         'body'   => $response->body(),
//     ]);
// }
public function handle()
{
    Log::info('WA_JOB_START', [
        'ticket_id' => $this->ticketId
    ]);
    $editTicketUrl = route('editopenticketforadmin', $hash);
    try {
        $ticket = Tickets::with('user.employee.store')
            ->findOrFail($this->ticketId);

        $createdAt = $ticket->created_at
            ->timezone('Asia/Makassar')
            ->format('d-m-Y H:i');

        $user = $ticket->user;
        $employee = $user?->employee;
        $store = $employee?->store;

        $userName =
            $employee->employee_name
            ?? $store->name
            ?? $user->username;

        $locationName = $store->name ?? '-';
        $phoneNumber  = $employee->telp_number ?? '-';

        $message = "*New IT Ticket*\n"
            ."Queue: {$ticket->queue_number}\n"
            ."Date: {$createdAt}\n"
            ."User: {$userName}\n"
            ."Location: {$locationName}\n"
            ."Phone Number: {$phoneNumber}\n"
            ."Title: {$ticket->title}\n"
            ."Category: {$ticket->category}\n"
            ."Description: {$ticket->description}\n"
            ."*Tickets Url Links*\n" 
            ."{$editTicketUrl}";
        if ($ticket->attachment_url) {
            $message .= "\nAttachments:\n{$ticket->attachment_url}";
        }
        $response = Http::timeout(10)->post(
            'http://127.0.0.1:3000/send-message',
            [
                'group_id' => '120363405189832865@g.us',
                'text'     => $message,
            ]
        );
        Log::info('WA_JOB_RESPONSE', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if (! $response->successful()) {
            throw new \Exception(
                'WA API failed: ' . $response->body()
            );
        }

    } catch (\Throwable $e) {
        Log::error('WA_JOB_FAILED', [
            'ticket_id' => $this->ticketId,
            'error' => $e->getMessage(),
        ]);

        throw $e;
    }
}

}

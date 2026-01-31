<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class SendOverdueTicketWhatsapp implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;
    public $timeout = 30;
    public function __construct(
        public string $ticketId
    ) {}
    public function handle(): void
    {
        Log::info('WA_OVERDUE_JOB_START', [
            'ticket_id' => $this->ticketId,
        ]);

        // $ticket = Tickets::find($this->ticketId);
//         $ticket = Tickets::with([
//     'executor.employee',
// ])->find($this->ticketId);
$ticket = Tickets::with('executor.employee')
    ->find($this->ticketId)
    ?->fresh();


        if (! $ticket || $ticket->status !== 'Overdue') {
            Log::warning('WA_OVERDUE_JOB_SKIPPED', [
                'ticket_id' => $this->ticketId,
                'status'    => $ticket?->status,
            ]);
            return;
        }
        $hash = substr(
            hash('sha256', $ticket->id . config('app.key')),
            0,
            8
        );
        $user = User::with('employee.store')->find($ticket->user_id);
        $adminUrl  = route('editopenticketforadmin', $hash);
        $employee = $user?->employee;
        $store = $employee?->store;
        $phoneNumber  = $employee->telp_number ?? '-';
        // $progressAt  = $ticket->progressed_at ?? '-';
    //    $progress = optional($ticket->progressed_at)
    //         ->timezone('Asia/Makassar')
    //         ->format('d-m-Y H:i');
$estimation = $ticket->estimation
    ? $ticket->estimation->timezone('Asia/Makassar')->format('d-m-Y H:i')
    : '-';
        $priorities = $ticket->priority ?? '-';
        $notesit = $ticket->notes_executor ?? '-';
        $createdAt = optional($ticket->created_at)
            ->timezone('Asia/Makassar')
            ->format('d-m-Y H:i');
        $executorName = $ticket->executor?->employee?->employee_name ?? '-';
        $message = implode("\n", [
            "WARNING TICKET OVERDUE ALERT",
            "Queue: {$ticket->queue_number}",
            "Date: {$createdAt}",
            "User: {$employee->employee_name}",
            "Location: {$store->name}",
            "Phone Number: {$phoneNumber}",
            "Title: {$ticket->title}",
            "Priority: {$priorities}",
            "Executor: {$executorName}",
           "Progress: " . (
    $ticket->progressed_at
        ? $ticket->progressed_at->timezone('Asia/Makassar')->format('d-m-Y H:i')
        : '-'
),
            "Notes IT: {$notesit}",
            "Estimation: {$estimation}",
            "Ticket Link: {$adminUrl}",
            "ayo dihajar tim testing!!!.",
        ]);
        Http::timeout(10)->post(
            'http://127.0.0.1:3000/send-message',
            [
                'group_id' => '120363405189832865@g.us',
                'text'     => $message,
            ]
        );
        Log::info('WA_OVERDUE_JOB_SENT', [
            'ticket_id' => $ticket->id,
        ]);
    }
}

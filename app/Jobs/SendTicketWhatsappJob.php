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

class SendTicketWhatsappJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $timeout = 30;

    public function __construct(
        public string $ticketId,
        public ?string $oldStatus = null
    ) {}

    public function handle(): void
    {

        Log::info('WA_JOB_START', [
            'ticket_id'  => $this->ticketId,
            'old_status' => $this->oldStatus,
        ]);

        try {
            $ticket = Tickets::find($this->ticketId);

            if (! $ticket) {
                Log::warning('WA_JOB_TICKET_NOT_FOUND', [
                    'ticket_id' => $this->ticketId
                ]);
                return;
            }
            
            $hash = substr(
                hash('sha256', $ticket->id . config('app.key')),
                0,
                8
            );
            $editTicketUrl = config('app.url') . '/editopenticketforadmin/' . $hash;
            $reviewTicketUrl = config('app.url') . '/reviewtickets/' . $hash;
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

            // =============================
            // BASE MESSAGE
            // =============================
            $lines = [
                "IT Ticket Created",
                "Queue: {$ticket->queue_number}",
                "Date: {$createdAt}",
                "User: {$userName}",
                "Location: {$locationName}",
                "Phone: {$phoneNumber}",
                "Title: {$ticket->title}",
                "Category: {$ticket->category}",
                "Description: {$ticket->description}",
                "Status: Open",
                "Ticket Link: {$editTicketUrl}",
            ];
            // =============================
            // 🔥 ONLY IF PROGRESS → CLOSED
            // =============================
            if (
                $this->oldStatus === 'Progress'
                && $ticket->status === 'Closed'
            ) {
                $lines[] = "*Ticket Review*";
                $lines[] = $reviewTicketUrl;

                Log::info('WA_REVIEW_LINK_ADDED', [
                    'ticket_id' => $ticket->id
                ]);
            }

            $message = implode("\n", $lines);

            if (!empty($ticket->attachment_url)) {
                $message .= "\nAttachments:\n{$ticket->attachment_url}";
            }

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
            Log::error('WA_JOB_FAILED', [
                'ticket_id' => $this->ticketId,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}

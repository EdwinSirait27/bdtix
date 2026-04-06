<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendOpenTicketWhatsapp implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;
    public $timeout = 30;
    public function __construct(
        public string $ticketId
    ) {}
    public function handle(): void
    {
        Log::info('WA_OPEN_JOB_START', [
            'ticket_id' => $this->ticketId,
        ]);
        $ticket = Tickets::with('executor.employee')
            ->find($this->ticketId)
            ?->fresh();
        if (! $ticket || $ticket->status !== 'Open') {
            Log::warning('WA_OPEN_JOB_SKIPPED', [
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
        $user       = User::with('employee.store')->find($ticket->user_id);
        $adminUrl   = route('editopenticketforadmin', $hash);
        $employee   = $user?->employee;
        $store      = $employee?->store;
        $phoneNumber = $employee->telp_number ?? '-';
        $createdAt = optional($ticket->created_at)
            ->timezone('Asia/Makassar')
            ->format('d-m-Y H:i');
        $hoursOpen = (int) now('Asia/Makassar')
            ->diffInHours($ticket->created_at->timezone('Asia/Makassar'));
        $priorities = $ticket->priority ?? '-';
        $message = implode("\n", [
            "BD OPEN TICKET REMINDER",
            "Queue: {$ticket->queue_number}",
            "Date Created: {$createdAt}",
            "Hours Open: {$hoursOpen} jam",
            "User: {$employee->employee_name}",
            "Location: {$store->name}",
            "Phone Number: {$phoneNumber}",
            "Title: {$ticket->title}",
            "Categories: {$ticket->category}",
            "Sub Categories: {$ticket->sub_category}",
            "Priority: {$priorities}",
            "Ticket Link: {$adminUrl}",
            "Ticket ini belum diproses, tolong dibantu ya tim!",
        ]);
        Http::timeout(10)->post(
            'http://127.0.0.1:3001/send-message',
            [
                'group_id' => '120363424946872780@g.us',
                'text'     => $message,
            ]
        );
        Log::info('WA_OPEN_JOB_SENT', [
            'ticket_id'  => $ticket->id,
            'hours_open' => $hoursOpen,
        ]);
        // self::dispatch($this->ticketId)
        //     ->delay(now()->addHour());
        self::dispatch($this->ticketId)
    ->delay(now()->addMinutes(5));
    }
}

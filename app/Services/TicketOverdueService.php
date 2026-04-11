<?php

namespace App\Services;

use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOverdueTicketWhatsapp;

class TicketOverdueService
{
    public function markOverdue(): int
    {
        $now = Carbon::now();
        $count = 0;
        Tickets::whereIn('status', ['Open', 'Progress'])
            ->whereNotNull('priority')
            ->chunk(100, function ($tickets) use ($now, &$count) {
                foreach ($tickets as $ticket) {
                    $baseTime = $ticket->estimation_to
                        ? $ticket->estimation_to
                        : $ticket->created_at;

                    switch ($ticket->priority) {



                        case 'Low':
                            $dueTime = $baseTime->copy()->addMinutes(2);
                            break;
                        case 'Medium':
                            $dueTime = $baseTime->addMinutes(2);
                            break;
                        case 'High':
                            $dueTime = $baseTime->addMinutes(2);
                            break;


                        default:
                            continue 2;
                    }

                    // 3️⃣ Tandai overdue
                    if ($now->greaterThan($dueTime)) {
                        $oldStatus = $ticket->status;

                        $ticket->update([
                            'status' => 'Overdue',
                        ]);

                        SendOverdueTicketWhatsapp::dispatch($ticket->id)
                            ->onQueue('whatsappoverdue');

                        Log::info('TICKET_MARKED_OVERDUE', [
                            'ticket_id'     => $ticket->id,
                            'priority'      => $ticket->priority,
                            'old_status'    => $oldStatus,
                            'base_time'     => $baseTime->toDateTimeString(),
                            'due_time'      => $dueTime->toDateTimeString(),
                            'now'           => $now->toDateTimeString(),
                            'estimation_to' => optional($ticket->estimation_to)->toDateTimeString(),
                        ]);
                        $count++;
                    }
                }
            });
        return $count;
    }
}
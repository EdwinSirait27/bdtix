<?php
namespace App\Services;
use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class TicketOverdueService
{
    public function markOverdue(): int
    {
        $now = Carbon::now();
        $tickets = Tickets::whereIn('status', ['Open', 'Progress', 'Closed'])
            ->whereNotNull('estimation')
            ->where('estimation', '<', $now)
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'status' => 'Overdue',
            ]);

            Log::info('TICKET_MARKED_OVERDUE', [
                'ticket_id' => $ticket->id,
                'old_status' => $ticket->getOriginal('status'),
                'estimation' => $ticket->estimation,
                'now' => $now->toDateTimeString(),
            ]);
        }

        return $tickets->count();
    }
}

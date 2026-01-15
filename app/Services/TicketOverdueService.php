<?php

namespace App\Services;

use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TicketOverdueService
{
    // public function markOverdue(): int
    // {
    //     $now = Carbon::now();
    //     $tickets = Tickets::whereIn('status', ['Open', 'Progress', 'Closed'])
    //         ->whereNotNull('estimation')
    //         ->where('estimation', '<', $now)
    //         ->get();

    //     foreach ($tickets as $ticket) {
    //         $ticket->update([
    //             'status' => 'Overdue',
    //         ]);

    //         Log::info('TICKET_MARKED_OVERDUE', [
    //             'ticket_id' => $ticket->id,
    //             'old_status' => $ticket->getOriginal('status'),
    //             'estimation' => $ticket->estimation,
    //             'now' => $now->toDateTimeString(),
    //         ]);
    //     }

    //     return $tickets->count();
    // }
    public function markOverdue(): int
    {
        $now = Carbon::now();

        $tickets = Tickets::whereIn('status', ['Open', 'Progress'])
            ->whereNotNull('priority')
            ->get();
        $count = 0;
        foreach ($tickets as $ticket) {
            $createdAt = Carbon::parse($ticket->created_at);
            // tentukan batas waktu berdasarkan priority
            switch (strtolower($ticket->priority)) {
                case 'Low':
                    $dueTime = $createdAt->copy()->addHour(); // 1 jam
                    break;
                case 'Medium':
                    $dueTime = $createdAt->copy()->addHours(12); // 12 jam
                    break;
                case 'High':
                    $dueTime = $createdAt->copy()->addWeek(); // 1 minggu
                    break;
                default:
                    continue 2; // skip kalau priority tidak valid
            }
            if ($now->greaterThan($dueTime)) {
                $oldStatus = $ticket->status;
                $ticket->update([
                    'status' => 'Overdue',
                ]);
                Log::info('TICKET_MARKED_OVERDUE', [
                    'ticket_id'  => $ticket->id,
                    'priority'   => $ticket->priority,
                    'old_status' => $oldStatus,
                    'created_at' => $createdAt->toDateTimeString(),
                    'due_time'   => $dueTime->toDateTimeString(),
                    'now'        => $now->toDateTimeString(),
                ]);
                $count++;
            }
        }
        return $count;
    }
}

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
                $createdAt = $ticket->created_at;

                switch ($ticket->priority) {
                    case 'Low':
                        $dueTime = $createdAt->copy()->addMinutes(3);
                        break;

                    case 'Medium':
                        $dueTime = $createdAt->copy()->addMinutes(5);
                        break;

                    case 'High':
                        $dueTime = $createdAt->copy()->addMinutes(7);
                        break;

                    default:
                        continue;
                }
                if ($now->greaterThan($dueTime)) {
                    $oldStatus = $ticket->status;
                    $ticket->update([
                        'status' => 'Overdue',
                    ]);
SendOverdueTicketWhatsapp::dispatch($ticket->id)
                        ->onQueue('whatsappoverdue');
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
        });

    return $count;
}
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
//     public function markOverdue(): int
//     {
//         $now = Carbon::now();

//         $tickets = Tickets::whereIn('status', ['Open', 'Progress'])
//             ->whereNotNull('priority')
//             ->get();
//         $count = 0;
//         foreach ($tickets as $ticket) {
//             $createdAt = Carbon::parse($ticket->created_at);
            
//             switch ($ticket->priority) {
//     case 'Low':
//         $dueTime = $createdAt->copy()->addMinutes(3);
//         break;

//     case 'Medium':
//         $dueTime = $createdAt->copy()->addMinutes(5);
//         break;
//     case 'High':
//         $dueTime = $createdAt->copy()->addMinutes(7);
//         break;
//     default:
//         continue 2; // skip kalau priority tidak valid
// }
//             if ($now->greaterThan($dueTime)) {
//                 $oldStatus = $ticket->status;
//                 $ticket->update([
//                     'status' => 'Overdue',
//                 ]);
//                 Log::info('TICKET_MARKED_OVERDUE', [
//                     'ticket_id'  => $ticket->id,
//                     'priority'   => $ticket->priority,
//                     'old_status' => $oldStatus,
//                     'created_at' => $createdAt->toDateTimeString(),
//                     'due_time'   => $dueTime->toDateTimeString(),
//                     'now'        => $now->toDateTimeString(),
//                 ]);
//                 $count++;
//             }
//         }
//         return $count;
//     }
    // tentukan batas waktu berdasarkan priority
            // switch ($ticket->priority) {
            //     case 'Low':
            //         $dueTime = $createdAt->copy()->addHour(); 
            //         break;
            //     case 'Medium':
            //         $dueTime = $createdAt->copy()->addHours(12);
            //         break;
            //     case 'High':
            //         $dueTime = $createdAt->copy()->addWeek(); // 1 minggu
            //         break;
            //     default:
            //         continue 2; // skip kalau priority tidak valid
            // }
}

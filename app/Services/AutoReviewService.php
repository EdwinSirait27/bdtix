<?php

namespace App\Services;

use App\Models\Tickets;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoReviewService
{
    public function run(): int
    {
        $tickets = Tickets::whereIn('status', ['Closed'])
            ->whereNull('review') // belum ada review
            ->whereNotNull('finished') // harus ada datetime patokan
            ->where('finished', '<=', Carbon::now()->subDay()) // finished + 1 hari
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->review()->create([
                'id'          => (string) Str::uuid(),
                'ticket_id'   => $ticket->id,
                'user_id'     => $ticket->user_id,
                'executor_id' => $ticket->executor_id,
                'rating'      => 5,
                'comment'     => 'Bintang 5 tapi ku bukan ancaman',
            ]);

            Log::info('AUTO_REVIEW_CREATED', [
                'ticket_id' => $ticket->id,
                'finished'  => $ticket->finished,
                'auto_at'   => Carbon::now()->toDateTimeString(),
            ]);
        }
        return $tickets->count();
    }
}
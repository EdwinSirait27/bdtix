<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Tickets;
use Illuminate\Support\Facades\Log;

class AutoTicketReview extends Command
{
    protected $signature = 'ticket:auto-review';
    protected $description = 'Create automatic review for closed tickets';

    public function handle(): int
    {
        $tickets = Tickets::where('status', 'Closed')
            ->whereDoesntHave('review')
            ->where('finished', '<=', now()->subDay())
            ->get();

        foreach ($tickets as $ticket) {

            if ($ticket->review()->exists()) {
                continue;
            }

            $ticket->review()->create([
                'user_id'     => $ticket->user_id,
                'executor_id' => $ticket->executor_id,
                'rating'      => 5,
                'comment'     => 'Bintang 5 tapi ku bukan ancaman',
            ]);

            Log::info('AUTO_REVIEW_CREATED', [
                'ticket_id' => $ticket->id,
                'auto_at'   => now(),
            ]);
        }

        $this->info("Auto review created: {$tickets->count()}");

        return Command::SUCCESS;
    }
}


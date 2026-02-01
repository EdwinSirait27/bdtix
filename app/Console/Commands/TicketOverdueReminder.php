<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Tickets;
use App\Jobs\SendOverdueTicketWhatsapp;


class TicketOverdueReminder extends Command
{
   
    protected $signature = 'tickets:send-overdue-reminder';
    protected $description = 'Send nightly WhatsApp reminder for overdue tickets';

    public function handle(): int
    {
        $now = now();
        $count = 0;

        Tickets::where('status', 'Overdue')
            ->where(function ($q) use ($now) {
                $q->whereNull('last_overdue_reminder_at')
                  ->orWhereDate('last_overdue_reminder_at', '<', $now->toDateString());
            })
            ->chunkById(100, function ($tickets) use (&$count, $now) {
                foreach ($tickets as $ticket) {
                    SendOverdueTicketWhatsapp::dispatch($ticket->id)
                        ->onQueue('whatsappoverdue');

                    $ticket->update([
                        'last_overdue_reminder_at' => $now,
                    ]);
                    $count++;
                }
            });

        $this->info("Overdue reminder sent: {$count}");

        return Command::SUCCESS;
    }
}
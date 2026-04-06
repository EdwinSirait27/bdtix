<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Tickets;
use App\Jobs\SendOpenTicketWhatsapp;

class OpenTicketReminder extends Command
{
   protected $signature = 'ticket:dispatch-open-reminder
                            {--ticket_id= : Dispatch untuk 1 ticket ID tertentu}';

    protected $description = 'Dispatch SendOpenTicketWhatsapp job untuk semua ticket berstatus Open';

    public function handle(): int
    {
        $ticketId = $this->option('ticket_id');

        if ($ticketId) {
            // Dispatch untuk 1 ticket tertentu
            $ticket = Tickets::where('id', $ticketId)
                ->where('status', 'Open')
                ->first();

            if (! $ticket) {
                $this->error("Ticket ID {$ticketId} tidak ditemukan atau statusnya bukan Open.");
                return self::FAILURE;
            }

            SendOpenTicketWhatsapp::dispatch($ticket->id);
            $this->info("Job dispatched untuk ticket: {$ticket->id} | Queue: {$ticket->queue_number}");

            return self::SUCCESS;
        }

        // Dispatch untuk semua ticket Open
        $tickets = Tickets::where('status', 'Open')->get();

        if ($tickets->isEmpty()) {
            $this->info('Tidak ada ticket dengan status Open.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($tickets->count());
        $bar->start();

        foreach ($tickets as $ticket) {
            SendOpenTicketWhatsapp::dispatch($ticket->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Total {$tickets->count()} job berhasil di-dispatch.");

        return self::SUCCESS;
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TicketOverdueService;

class MarkTicketOverdue extends Command
{
    protected $signature = 'tickets:mark-overdue';
    protected $description = 'Mark tickets as overdue if estimation has passed';
    public function handle(TicketOverdueService $service)
    {
        $count = $service->markOverdue();
        $this->info("{$count} ticket(s) marked as overdue");
    }
}
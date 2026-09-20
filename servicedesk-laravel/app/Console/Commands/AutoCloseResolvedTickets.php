<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\MongoDB\TicketHistory;
use App\Models\MongoDB\ActivityLog;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoCloseResolvedTickets extends Command
{
    protected $signature = 'tickets:auto-close-resolved';
    protected $description = 'Automatically close tickets that have been in Resolved status for more than 3 days';

    public function handle()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);

        $tickets = Ticket::where('status', 'Resolved')
            ->where('updated_at', '<=', $threeDaysAgo)
            ->get();

        if ($tickets->isEmpty()) {
            $this->info('No tickets to auto-close.');
            return;
        }

        foreach ($tickets as $ticket) {
            $ticket->update(['status' => 'Closed']);

            TicketHistory::logChange(
                $ticket->id,
                'status_changed',
                'Resolved',
                'Closed',
                null, 
                'Auto-closed after 3 days in Resolved status'
            );

            ActivityLog::log(
                null,
                'ticket_auto_closed',
                "Ticket {$ticket->ticket_id} automatically closed after 3 days in Resolved status",
                ['ticket_id' => $ticket->id, 'reason' => 'auto_close_3_days']
            );

            $this->info("Ticket {$ticket->ticket_id} auto-closed.");
        }

        $this->info("Total {$tickets->count()} ticket(s) auto-closed.");
    }
}
<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Notifications\LoanOverdue;
use Illuminate\Console\Command;

class SendLoanReminders extends Command
{
    protected $signature = 'activos:loan-reminders';
    protected $description = 'Envía recordatorios para préstamos de equipos vencidos (>30 días)';

    public function handle(): int
    {
        $overdueAssignments = Assignment::with(['device', 'user'])
            ->whereNull('returned_at')
            ->whereNotNull('user_id')
            ->where('assigned_at', '<', now()->subDays(30))
            ->get();

        $count = 0;
        foreach ($overdueAssignments as $assignment) {
            if ($assignment->user) {
                $assignment->user->notify(new LoanOverdue($assignment));
                $count++;
            }
        }

        $this->info("Se enviaron {$count} recordatorios de préstamos vencidos.");

        return Command::SUCCESS;
    }
}

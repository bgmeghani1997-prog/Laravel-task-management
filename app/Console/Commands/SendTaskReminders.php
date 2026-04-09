<?php

namespace App\Console\Commands;

use App\Mail\TaskReminder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for tasks due tomorrow';

    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();

        $users = User::whereHas('tasks', function ($query) use ($tomorrow) {
            $query->where('due_date', $tomorrow)->where('status', '!=', 'completed');
        })->with(['tasks' => function ($query) use ($tomorrow) {
            $query->where('due_date', $tomorrow)->where('status', '!=', 'completed');
        }])->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new TaskReminder($user, $user->tasks));
        }

        $this->info('Task reminders sent successfully.');
    }
}

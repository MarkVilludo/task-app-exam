<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Carbon;

class DeleteOldTrashedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-trashed-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete tasks that have been in the trash for more than 30 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Task::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(config('tasks.schedule_cleanup')))
            ->forceDelete();
        $this->info("Deleted $count old trashed tasks.");
    }
}

<?php

namespace App\Actions\Task;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Actions\Task\Traits\AuthorizeTaskOwner;

class DeleteTaskAction
{
    use AsAction;
    use AuthorizeTaskOwner;

    public function handle(Task $task)
    {
        $this->authorizeTaskOwner($task);
        $task->delete();
        return response()->json(['message' => 'Task deleted.']);
    }

    public function asController(Task $task)
    {
        return $this->handle($task);
    }
}

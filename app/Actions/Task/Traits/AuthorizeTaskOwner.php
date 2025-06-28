<?php

namespace App\Actions\Task\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;

trait AuthorizeTaskOwner
{
    public function authorizeTaskOwner(Task $task)
    {
        $user = Auth::user();
        if ($task->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
    }
} 
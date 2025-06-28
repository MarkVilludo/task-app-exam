<?php

namespace App\Actions\Task;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Task\UpdateTaskRequest;
use Illuminate\Support\Facades\Storage;
use App\Actions\Task\Traits\AuthorizeTaskOwner;

class UpdateTaskAction
{
    use AsAction, AuthorizeTaskOwner;

    public function handle(Task $task, array $data)
    {
        $this->authorizeTaskOwner($task);
        if (isset($data['image']) && $data['image']) {
            if ($task->image_path) {
                Storage::disk('public')->delete($task->image_path);
            }
            $data['image_path'] = $data['image']->store('tasks', 'public');
        }
        unset($data['image']);

        // Sync subtasks if provided
        if (isset($data['subtasks']) && is_array($data['subtasks'])) {
            $task->subtasks()->sync($data['subtasks']);
        }

        // Update subtask statuses if provided
        if (isset($data['subtask_statuses']) && is_array($data['subtask_statuses'])) {
            foreach ($data['subtask_statuses'] as $subId => $status) {
                $subtask = Task::find($subId);
                if ($subtask) {
                    $subtask->status = $status;
                    $subtask->save();
                }
            }
        }

        // Remove subtasks and subtask_statuses from $data before updating the task itself
        unset($data['subtasks'], $data['subtask_statuses']);

        $task->update($data);
        return $task->load('subtasks');
    }

    public function asController(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }
        $task = $this->handle($task, $validated);
        return response()->json($task);
    }
}

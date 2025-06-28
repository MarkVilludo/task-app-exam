<?php

namespace App\Actions\Task;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Task\StoreTaskRequest;
use Illuminate\Support\Facades\Storage;

class CreateTaskAction
{
    use AsAction;

    public function handle(array $data)
    {
        $user = Auth::user();
        $imagePath = null;
        if (isset($data['image']) && $data['image']) {
            $imagePath = $data['image']->store('tasks', 'public');
        }
        if (isset($data['subtasks']) && is_string($data['subtasks'])) {
            // Accept JSON string from UI
            $data['subtasks'] = json_decode($data['subtasks'], true);
        }
        $task = Task::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
            'image_path' => $imagePath,
            'is_draft' => $data['is_draft'] ?? false,
        ]);
        // Sync subtasks if provided as an array of IDs
        if (isset($data['subtasks']) && is_array($data['subtasks'])) {
            $task->subtasks()->sync($data['subtasks']);
        }
        return $task->load('subtasks');
    }

    public function asController(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }
        $task = $this->handle($validated);
        return response()->json($task, 201);
    }
}

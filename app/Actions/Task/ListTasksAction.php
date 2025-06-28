<?php

namespace App\Actions\Task;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ListTasksAction
{
    use AsAction;

    public function handle(array $filters = [])
    {

        $user = Auth::user();
        $query = Task::with([
            'subtasks:id,title,status',
            'parentTasks:id,title,status'
        ])->where('user_id', $user->id);

        if (!empty($filters['search'])) {
            $query->searchTitle($filters['search']);
        }
        if (!empty($filters['status'])) {
            $query->status($filters['status']);
        }
        if (!empty($filters['order_by'])) {
            $direction = $filters['direction'] ?? 'asc';
            $query->ordered($filters['order_by'], $direction);
        }
        if (!empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 10;
        $list = $query->paginate($perPage);

        // Transform each task to include subtasks and parentTasks as arrays
        $list->getCollection()->transform(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'content' => $task->content,
                'status' => $task->status,
                'image_path' => $task->image_path,
                'is_draft' => $task->is_draft,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
                'subtasks' => $task->subtasks->map(fn($sub) => [
                    'id' => $sub->id,
                    'title' => $sub->title,
                    'status' => $sub->status,
                ]),
                'parentTasks' => $task->parentTasks->map(fn($parent) => [
                    'id' => $parent->id,
                    'title' => $parent->title,
                    'status' => $parent->status,
                ]),
            ];
        });

        return $list;
    }

    public function asController(Request $request)
    {
        $filters = $request->only(['search', 'status', 'order_by', 'direction', 'per_page']);
        $tasks = $this->handle($filters);
        return response()->json($tasks);
    }
}

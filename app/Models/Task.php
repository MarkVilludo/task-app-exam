<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'image_path',
        'is_draft', //this is used to indicate if the task is a draft or (published if false)
    ];

    protected $casts = [
        'is_draft' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for filtering by status
    public function scopeStatus($query, $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    // Scope for searching by title
    public function scopeSearchTitle($query, $search)
    {
        if ($search) {
            $query->where('title', 'like', "%$search%");
        }
        return $query;
    }

    // Scope for ordering
    public function scopeOrdered($query, $orderBy = 'created_at', $direction = 'desc')
    {
        return $query->orderBy($orderBy, $direction);
    }

    // Tasks that are subtasks of this task
    public function subtasks()
    {
        return $this->belongsToMany(Task::class, 'subtask_task', 'task_id', 'subtask_id');
    }

    // Tasks that this task is a subtask of
    public function parentTasks()
    {
        return $this->belongsToMany(Task::class, 'subtask_task', 'subtask_id', 'task_id');
    }

    protected static function booted()
    {
        parent::booted();
        static::saved(function ($task) {
            // For each parent task, check if all subtasks are done
            foreach ($task->parentTasks as $parent) {
                $total = $parent->subtasks()->count();
                $done = $parent->subtasks()->where('status', 'done')->count();
                if ($total > 0 && $done == $total) {
                    $parent->status = 'done';
                    $parent->save();
                }
            }
        });
    }
}

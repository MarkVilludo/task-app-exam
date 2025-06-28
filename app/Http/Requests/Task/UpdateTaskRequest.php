<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow if user is authenticated
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $taskId = $this->route('task') ? $this->route('task')->id : null;
        return [
            'title' => 'required|string|max:100|unique:tasks,title,' . $taskId,
            'content' => 'required|string',
            'status' => 'required|in:to-do,in-progress,done',
            'image' => 'nullable|image|max:4096', // 4MB
            'is_draft' => 'boolean',
            'subtasks' => 'nullable|array',
            'subtask_statuses' => 'nullable|array',
        ];
    }
}

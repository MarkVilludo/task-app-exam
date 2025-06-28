<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:100|unique:tasks,title',
            'content' => 'required|string',
            'status' => 'required|in:to-do,in-progress,done',
            'image' => 'nullable|image|max:4096', // 4MB
            'is_draft' => 'boolean',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'exists:tasks,id|distinct',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.unique' => 'The title has already been taken.',
            'title.max' => 'The title may not be greater than 100 characters.',
            'content.required' => 'The content field is required.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of: to-do, in-progress, done.',
            'image.image' => 'The attachment must be an image.',
            'image.max' => 'The image may not be greater than 4MB.',
            'subtasks.array' => 'The subtasks field must be an array.',
            'subtasks.*.exists' => 'One or more selected subtasks do not exist.',
            'subtasks.*.distinct' => 'Duplicate subtasks are not allowed.',
        ];
    }
}

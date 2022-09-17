<?php

namespace App\Http\Requests;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required|date|date_format:Y-m-d',
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'status' => Rule::in(array_column(TaskStatusEnum::cases(), 'value')),
            'priority' => Rule::in(array_column(TaskPriorityEnum::cases(), 'value')),
        ];
    }
}

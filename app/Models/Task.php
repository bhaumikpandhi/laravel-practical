<?php

namespace App\Models;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'priority' => TaskPriorityEnum::class
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($task) {
            $task->load('notes.noteFiles');

            foreach ($task->notes as $note) {
                foreach ($note->noteFiles as $file) {
                    $file->delete();
                }
                $note->delete();
            }
        });
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request): Builder
    {
        if ($request->has('order') && $request->get('order') === 'priority') {
            $priorities = array_column(TaskPriorityEnum::cases(), 'value');
            if ($request->get('order_direction') === 'desc') {
                $priorities = array_reverse($priorities);
            }
            $priorityString = implode('","', $priorities);
            $query->orderByRaw('FIELD(priority, "' . $priorityString . '")');
        } else if ($request->has('order') && $request->get('order') === 'notes_count') {
            $query->orderBy('notes_count', $request->get('order_direction', 'asc'));
        }

        if ($request->has('filter.status') && $request->input('filter.status')) {
            $query->where('status', $request->input('filter.status'));
        }

        if ($request->has('filter.priority') && $request->input('filter.priority')) {
            $query->where('priority', $request->input('filter.priority'));
        }

        if ($request->has('filter.due_date') && $request->input('filter.due_date')) {
            $query->where('due_date', $request->input('filter.due_date'));
        }

        if ($request->has('filter.notes') && $request->input('filter.notes')) {
            $query->having('notes_count', '=', $request->input('filter.notes'));
        }

        return $query;
    }
}

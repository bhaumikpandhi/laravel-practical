<?php

namespace App\Models;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'priority' => TaskPriorityEnum::class
    ];

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

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
}

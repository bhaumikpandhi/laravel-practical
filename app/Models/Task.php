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
}

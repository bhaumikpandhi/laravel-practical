<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function noteFiles(): HasMany
    {
        return $this->hasMany(NoteFile::class);
    }
}

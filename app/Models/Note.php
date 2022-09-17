<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($note) {
            foreach ($note->noteFiles as $file) {
                $file->delete();
            }
        });
    }

    public function noteFiles(): HasMany
    {
        return $this->hasMany(NoteFile::class);
    }
}

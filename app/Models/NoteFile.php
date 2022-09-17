<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NoteFile extends Model
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
        static::deleting(function ($file) {
            Storage::disk('public')->delete('uploads/' . $file->path);
        });
    }

    public function getFileURLAttribute()
    {
        return Storage::disk('public')->url('uploads/' . $this->path);
    }
}

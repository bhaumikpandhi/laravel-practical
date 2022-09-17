<?php

namespace App\Services;

use App\Models\NoteFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function uploadNoteFiles(array $files, $noteId)
    {
        foreach ($files as $file) {
            $path = $file->store('uploads', 'public');
            $baseName = basename($path);
            NoteFile::query()->create([
                'note_id' => $noteId,
                'path' => $baseName,
                'size' => Storage::disk('public')->size($path),
                'extension' => File::extension($path)
            ]);
        }
    }
}

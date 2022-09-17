<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NoteFile>
 */
class NoteFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if (!Storage::disk('public')->exists('uploads')) {
            Storage::disk('public')->makeDirectory('uploads');
        }
        $file = $this->faker->file(storage_path('app/random-images'), storage_path('app/public/uploads'), false);
        return [
            'note_id' => Note::factory(),
            'path' => $file,
            'size' => Storage::disk('public')->size('uploads/' . $file),
            'extension' => File::extension('uploads/' . $file)
        ];
    }
}

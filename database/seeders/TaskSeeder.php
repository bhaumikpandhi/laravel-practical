<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\NoteFile;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::factory()
            ->has(
                Note::factory()
                    ->has(NoteFile::factory()->count(2))
                    ->count(3)
            )
            ->count(5)
            ->create();
    }
}

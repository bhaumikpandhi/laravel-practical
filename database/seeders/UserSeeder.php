<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\NoteFile;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Authorised User',
            'email' => 'authorised@gmail.com'
        ]);
        User::factory()->create([
            'name' => 'Unauthorised User',
            'email' => 'unauthorised@gmail.com'
        ]);
        User::factory()
            ->has(
                Task::factory()
                    ->has(
                        Note::factory()
                            ->has(
                                NoteFile::factory()->count(2)
                            )
                            ->count(3)
                    )
                    ->count(5)
            )
            ->count(5)
            ->create();
    }
}

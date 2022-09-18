<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskAPITest extends TestCase
{

    use WithFaker;

    private Task $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new Task();
    }

    /**
     * Asset that not logged-in user should is not allowed to hit create task API.
     *
     * @return void
     */
    public function test_task_create_api_without_login()
    {
        $response = $this->json('POST', '/api/tasks', Task::factory()->make()->toArray());

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Asset that not logged-in user should is not allowed to hit create task API.
     *
     * @return void
     */
    public function test_task_create_api_with_login()
    {
        $startDate = Carbon::make(now());
        $user = User::query()->findOrFail(1);

        $data = Task::factory()
            ->state([
                'start_date' => $startDate->format('Y-m-d'),
                'due_date' => $startDate->addDays(2)->format('Y-m-d'),
            ])
            ->make()
            ->toArray();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/tasks', $data);
        
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Task created successfully',
            ]);
    }
}

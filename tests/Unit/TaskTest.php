<?php

namespace Tests\Unit;

use App\Enum\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;

    private Task $model;
    private User $userModel;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new Task();
        $this->userModel = new User();
    }

    private function create_task()
    {
        return Task::factory()->create();
    }

    /**
     * Assertion case to test task creation
     *
     * @return void
     */
    public function test_create_task()
    {
        $task = $this->create_task();
        $this->assertDatabaseHas($this->userModel->getTable(), ['id' => $task->user_id]);
        $this->assertDatabaseHas($this->model->getTable(), ['id' => $task->id]);
    }

    public function test_task_update()
    {
        $task = $this->create_task();

        $data = [
            'title' => $this->faker->sentence(),
        ];

        $updated = $task->update($data);
        $updatedTask = Task::query()->find($task->id);
        $this->assertTrue($updated);
        $this->assertEquals($data['title'], $updatedTask->title);
    }

    public function test_task_delete()
    {
        $record = $this->create_task();

        $taskRecord = Task::query()->find($record->id);
        $id = $taskRecord->id;
        $taskRecord->delete();
        $this->assertDatabaseMissing($this->model->getTable(), ['id' => $id]);
    }
}

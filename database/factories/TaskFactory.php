<?php

namespace Database\Factories;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'user_id' => User::factory(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->date(),
            'due_date' => fake()->date(),
            'status' => fake()->randomElement(array_column(TaskStatusEnum::cases(), 'value')),
            'priority' => fake()->randomElement(array_column(TaskPriorityEnum::cases(), 'value')),
        ];
    }
}

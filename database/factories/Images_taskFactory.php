<?php

namespace Database\Factories;

use App\Models\images_task;
use App\Models\Task;
use App\Models\Images;
use Illuminate\Database\Eloquent\Factories\Factory;

class Images_taskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = images_task::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'images_id'     => $this->faker->randomElement(Images::all())['id'],
            'task_id'       => $this->faker->randomElement(Task::all())['id'],
        ];
    }
}

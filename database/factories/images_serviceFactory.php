<?php

namespace Database\Factories;

use App\Models\images_service;
use App\Models\Services;
use App\Models\Images;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class images_serviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = images_service::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'images_id'     => $this->faker->randomElement(Images::all())['id'],
            'services_id'   => $this->faker->randomElement(Services::all())['id'],
        ];
    }
}

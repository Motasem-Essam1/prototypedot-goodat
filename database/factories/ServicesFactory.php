<?php

namespace Database\Factories;
use App\Models\Services;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Services::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    { // Services::factory()->count(5)->create()
        return [
            'user_id'               => $this->faker->randomElement(User::all())['id'],
            'parent_category_id'    => $this->faker->randomElement(Category::all())['id'],
            'category_id'           => $this->faker->randomElement(SubCategory::all())['id'],
            'service_name'          => $this->faker->unique()->name,
            'service_slug'          => Str::slug($this->faker->name),
            'service_description'   => $this->faker->paragraph,
            'created_at'            => now(),
            'updated_at'            => now(),
            'starting_price'        => $this->faker->randomFloat(2, 0, 1000),
            'ending_price'          => $this->faker->randomFloat(3, 0, 2000),
            'location_lng'          => $this->faker->longitude,
            'location_lat'          => $this->faker->latitude,
            'is_active'             => 1,
        ];
    }
}

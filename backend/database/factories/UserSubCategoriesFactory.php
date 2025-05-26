<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\SubCategory;
use App\Models\UserSubCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSubCategoriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSubCategories::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'           => $this->faker->randomElement(User::all())['id'],
            'user_category_id'  => $this->faker->randomElement(SubCategory::all())['id'],
        ];
    }
}

<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserData::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $providerType =[
            'web',
            'admin',
            'mobile'
        ];

        return [
            'user_id'               => $this->faker->unique()->randomElement(User::all())['id'],
            'package_id'            => 4,
            'verify_code'           => '94212',
            'user_type'             => 'Service Provider',
            'provider_id'           => 1,
            'provider_type'         => $providerType[rand(0, 2)],
            'avatar'                => '/assets/img/ninja.svg',
            'generated_Code'        => $this->faker->randomFloat(0, 0, 100000),
            'created_at'            => now(),
            'updated_at'            => now(),
        ];
    }
}

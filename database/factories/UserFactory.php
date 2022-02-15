<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserStatus;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status_id' => function () {
                return UserStatus::inRandomOrder()->first()->id;
            },
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'birthdate' => $this->faker->date('Y-m-d', '-18 years'),
            'phone' => '+63' . $this->faker->bothify('##########'),
            'gender' => $this->faker->boolean,
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt('123456'),
            'provider_id' => -1,
            'provider_name' => 'seeder'
        ];
    }
}

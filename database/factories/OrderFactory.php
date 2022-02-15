<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\OrderStatus;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::whereRelation('roles', 'name', 'customer')->inRandomOrder()->first();
        $orderStatus = OrderStatus::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'status_id' => $orderStatus->id
        ];
    }
}

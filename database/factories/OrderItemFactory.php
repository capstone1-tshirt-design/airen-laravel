<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Order;

class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $order = Order::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => intval($this->faker->numerify('###.##')),
            'quantity' => rand(1, 20)
        ];
    }
}

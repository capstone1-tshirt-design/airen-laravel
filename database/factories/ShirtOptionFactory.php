<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrderItem;

class ShirtOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orderItem = OrderItem::whereRelation('product.categories', 'name', 'shirt')->inRandomOrder()->first();

        return [
            'order_item_id' => $orderItem->id,
            'collar' => intval($this->faker->numerify('##.##')),
            'shirt_length' => intval($this->faker->numerify('##.##')),
            'sleeve_length' => intval($this->faker->numerify('##.##')),
            'shoulder' => intval($this->faker->numerify('##.##')),
            'chest' => intval($this->faker->numerify('##.##')),
            'tummy' => intval($this->faker->numerify('##.##')),
            'hips' => intval($this->faker->numerify('##.##')),
            'cuff' => intval($this->faker->numerify('##.##'))
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    private static $order = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isSale = rand(0, 1);
        $createdBy = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super administrator', 'administrator']);
        })->inRandomOrder()->first();
        $updatedBy = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super administrator', 'administrator']);
        })->inRandomOrder()->first();
        return [
            'name' => 'Product ' . self::$order++,
            'code' => Str::upper($this->faker->bothify('???##')),
            'description' => $this->faker->sentence,
            'sale' =>  $isSale ? true : false,
            'old_price' => $isSale ? intval($this->faker->numerify('###.##')) : null,
            'price' => intval($this->faker->numerify('###.##')),
            'created_by_id' => $createdBy->id,
            'updated_by_id' => $updatedBy->id,
        ];
    }
}

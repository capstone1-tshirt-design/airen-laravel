<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $extensions = ['jpg', 'gif', 'png'];
        $extension = array_rand($extensions);
        return [
            'url' => 'https://dummyimage.com/300x300/000/fff',
            'name' => '300x300',
            'extension' => $extensions[$extension],
            'size' => 300
        ];
    }
}

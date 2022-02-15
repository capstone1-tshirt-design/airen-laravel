<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Image;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Support\Facades\App;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'development'])) {
            Product::factory()->count(50)->create()
                ->each(function ($product) {
                    $categories = Category::inRandomOrder()->limit(rand(1, 3))->get();

                    $product->categories()->attach($categories);
                    $product->images()->saveMany(Image::factory()->count(rand(3, 6))->make());
                });
        }
    }
}

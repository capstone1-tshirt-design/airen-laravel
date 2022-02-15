<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use Illuminate\Support\Facades\App;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'development'])) {
            Review::factory()->count(100)->create();
        }
    }
}

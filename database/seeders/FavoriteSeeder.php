<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use Illuminate\Support\Facades\App;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'development'])) {
            Favorite::factory()->count(100)->create();
        }
    }
}

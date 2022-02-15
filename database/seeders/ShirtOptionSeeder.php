<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShirtOption;
use Illuminate\Support\Facades\App;

class ShirtOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'development'])) {
            ShirtOption::factory()->count(50)->create();
        }
    }
}

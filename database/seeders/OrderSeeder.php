<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\App;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'development'])) {
            Order::factory()->count(50)->create();
        }
    }
}

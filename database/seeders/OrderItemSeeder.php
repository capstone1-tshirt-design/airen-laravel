<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use Illuminate\Support\Facades\App;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'development'])) {
            OrderItem::factory()->count(50)->create();
        }
    }
}

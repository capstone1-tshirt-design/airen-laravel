<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'pending', // blue
            'declined', // yellow
            'on hold', // black
            'cancelled', // red
            'completed' // green
        ];

        foreach ($statuses as $status) {
            $c =  new OrderStatus;
            $c->name = $status;
            $c->save();
        }
    }
}

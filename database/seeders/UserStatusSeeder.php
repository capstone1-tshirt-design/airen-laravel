<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserStatus;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'active',
            'blocked',
            'inactive',
        ];

        foreach ($statuses as $status) {
            $s =  new UserStatus;
            $s->name = $status;
            $s->save();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserStatusSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            OrderStatusSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            ShirtOptionSeeder::class,
            FavoriteSeeder::class,
            ReviewSeeder::class,
        ]);
        echo "Deleting all files and directory in airen-s3 bucket\n";
        if (Storage::exists('uploads')) {
            Storage::deleteDirectory('uploads/' . App::environment());
        }
        echo "Successfully deleted all files and directory in airen-s3 bucket\n";
    }
}

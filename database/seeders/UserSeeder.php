<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Image;
use App\Models\UserStatus;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activeStatus = UserStatus::where('name', 'active')->first();
        $user = new User;
        $user->first_name = 'Super';
        $user->last_name = 'Administrator';
        $user->username = 'superadmin';
        $user->email = env('MAIL_USERNAME');
        $user->gender = true;
        $user->password = bcrypt('123456');
        $user->provider_id = -1;
        $user->provider_name = 'system';

        $user->status()->associate($activeStatus);
        $user->save();
        $user->markEmailAsVerified();

        $user->assignRole(Role::findByName('super administrator', 'api'));

        if (App::environment(['local', 'development'])) {
            User::factory()->count(10)->create()->each(function ($user) {
                $extensions = ['jpg', 'gif', 'png'];
                $extension = array_rand($extensions);
                $role = Role::findByName('administrator', 'api');
                $user->image()->save(Image::factory()->state([
                    'url' => 'https://dummyimage.com/250x250/000/fff',
                    'name' => '250x250',
                    'extension' => $extension,
                    'size' => 250
                ])->make());

                $user->markEmailAsVerified();
                $user->assignRole($role);
            });

            User::factory()->count(50)->create([
                'status_id' => $activeStatus->id
            ])->each(function ($user) {
                $extensions = ['jpg', 'gif', 'png'];
                $extension = array_rand($extensions);
                $role = Role::findByName('customer', 'api');
                $user->image()->save(Image::factory()->state([
                    'url' => 'https://dummyimage.com/250x250/000/fff',
                    'name' => '250x250',
                    'extension' => $extension,
                    'size' => 250
                ])->make());

                $user->markEmailAsVerified();
                $user->assignRole($role);
            });
        }
    }
}

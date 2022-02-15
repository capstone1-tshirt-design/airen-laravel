<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'women',
            'men',
            'summer collection',
            'winter collection',
            'shirt'
        ];
        $user = User::whereRelation('roles', 'name', 'super administrator');

        foreach ($categories as $category) {
            $c =  new Category;
            $c->name = $category;
            $c->description = Str::title($category);

            $c->createdBy()->associate($user->first());
            $c->updatedBy()->associate($user->first());

            $c->save();
        }
    }
}

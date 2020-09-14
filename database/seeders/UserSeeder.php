<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Image;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = User::factory(3)
            ->has(Image::factory()->count(3))
            ->create();

        // Laravel 8's factory methods are DREADFUL for this so we do it manually for reasons of time.
        $admins->each(function ($item, $key) {
            $item->assignRole ('admin');
            $item->createToken ('Personal Access Token');
        });

        $guests = User::factory(7)
            ->has(Image::factory()->count(3))
            ->create();
    }
}

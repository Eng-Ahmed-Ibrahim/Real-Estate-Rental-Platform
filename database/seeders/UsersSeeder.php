<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach(range(1,20) as $inder){
            User::create([
                "name"=>$faker->name(),
                "email"=>$faker->email(),
                "password"=>Hash::make($faker->password()),
            ]);
        }

    }
}


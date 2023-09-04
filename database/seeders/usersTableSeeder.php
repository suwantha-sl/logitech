<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker; 

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create(); 
        foreach(range(1, 5) as $index) { 
                DB::table('users')->insert([ 
                'name'     => $faker->name(), 
                'email'   => $faker->unique()->safeEmail ,
                'password' => bcrypt('password')
            ]); 
        }
        
    }
}

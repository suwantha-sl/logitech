<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker; 
use Illuminate\Support\Facades\DB;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $followerIds = DB::table('users')->pluck('id');
        $startDt = now()->subMonths(3);
        $endDt = now();                 

        foreach($followerIds as $ownerId){
            $faker = Faker::create();
            foreach(range(1, 400) as $index) { 
                    $randomDt = $faker->dateTimeBetween($startDt, $endDt)->format('Y-m-d');

                    $otherFollowerIds = $followerIds->reject(function ($value) use ($ownerId) {
                        return $value === $ownerId;
                    });
                    $randomFollowerId = $faker->randomElement($otherFollowerIds);

                    DB::table('followers')->insert([ 
                    'name'     => $faker->name(), 
                    'follower_id'   => $randomFollowerId,
                    'stream_owner'   => $ownerId,
                    'follow_start' => $randomDt,
                    'read_status' => $faker->randomElement(['Y','N'])                
                ]); 
            }
        }        
    }
}

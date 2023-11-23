<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Follow;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::All();

        foreach($users as $user){
            $random = User::All()->random(rand(1,4))->pluck('id');

            foreach($random as $following)
            if($following!=$user->id)
            Follow::factory()->create(['user_id'=> $user->id,'follow_id'=>$following] );
        }
    }
}

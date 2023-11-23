<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RateTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_new_rating()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('shows.store'),["show_id"=>"84958"]);

        $this->actingAs($user)->post(route('ratings.store'),['oldRating' => null, 'rating'=>10, 'showRecord'=>1,'s'=>1,'e'=>1,'step'=>1]);

        $this->assertDatabaseCount('ratings', 1);
    }

    public function test_modify_old_rating()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('shows.store'),["show_id"=>"84958"]);
        $this->actingAs($user)->post(route('ratings.store'),['oldRating' => null, 'rating'=>10, 'showRecord'=>1,'s'=>1,'e'=>1,'step'=>1]);

        $this->actingAs($user)->post(route('ratings.store'),['oldRating' => 1, 'rating'=>1, 'showRecord'=>1,'s'=>1,'e'=>1,'step'=>1]);

        $this->assertDatabaseHas('ratings', [
            'user_rating' =>1,
        ]);
    }
}

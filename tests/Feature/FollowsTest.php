<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Follow;

class FollowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_new_follow()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1)->post(route('follows.store'),["user_id"=>"2"]);
        $this->assertDatabaseCount('follows',1);
    }

    public function test_add_own_follow()
    {
        $user1 = User::factory()->create();
        $response = $this->actingAs($user1)->post(route('follows.store'),["user_id"=>"1"]);
        $response->assertStatus(400);
    }

    public function test_add_already_added_follow()
    {
        User::factory()->create();
        User::factory()->create();
        $user1 = User::find(1);
        $this->actingAs($user1)->post(route('follows.store'),["user_id"=>"2"]);
        $this->actingAs($user1)->post(route('follows.store'),["user_id"=>"2"]);

        $this->assertDatabaseCount('follows',1);
    }

    public function test_delete_follow()
    {
        User::factory()->create();
        User::factory()->create();
        $user1 = User::find(1);
        $this->actingAs($user1)->post(route('follows.store'),["user_id"=>"2"]);

        $this->actingAs($user1)->delete(route('follows.destroy',2));

        $this->assertDatabaseCount('follows',0);
    }
}

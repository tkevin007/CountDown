<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_ban_user()
    {
        $user1 = User::factory()->create();
        $user1->role='Admin';

        $user2 = User::factory()->create();
        $this->actingAs($user1)->delete(route('admin.destroy',$user2->id));

        $this->assertNotEquals(User::all()->count(),User::withTrashed()->count());
    }

    public function test_unban_user()
    {
        $user1 = User::factory()->create();
        $user1->role='Admin';

        $user2 = User::factory()->create();
        $this->actingAs($user1)->delete(route('admin.destroy',$user2->id));
        $this->actingAs($user1)->post(route('admin.store'),["id" =>$user2->id]);

        $this->assertEquals(User::all()->count(),User::withTrashed()->count());
    }

    public function test_add_new_admin()
    {
        $user1 = User::factory()->create();
        $user1->role='Admin';

        $user2 = User::factory()->create();
        $this->actingAs($user1)->put(route('admin.update',$user2->id));

        $this->assertDatabaseHas('users', [
            'role'=>'Admin',
            'username' => $user2->username,
        ]);
    }

    public function test_revoke_admin()
    {
        $user1 = User::factory()->create();
        $user1->role='Admin';

        $user2 = User::factory()->create();

        $this->actingAs($user1)->put(route('admin.update',$user2->id));
        $this->actingAs($user1)->put(route('admin.update',$user2->id));

        $this->assertDatabaseHas('users', [
            'role'=>'User',
            'username' => $user2->username,
        ]);
    }

    public function test_ban_authorization()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->delete(route('admin.destroy',$user2->id));
        $response->assertStatus(401);
    }
}

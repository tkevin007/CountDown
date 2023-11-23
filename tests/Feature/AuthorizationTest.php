<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthorizationTest extends TestCase
{

    use RefreshDatabase;

    public function test_index_path_as_guest()
    {
        $response2 = $this->get('/');
        $response2->assertRedirect('login');
    }

    public function test_index_path_as_user()
    {
        $user = User::factory()->create();
        $response =$this->actingAs($user)->get('/');

        $response->assertRedirect('countDown');
    }


    public function test_login_path_as_guest()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_path_as_user()
    {
        $user = User::factory()->create();
        $response =$this->actingAs($user)->get('/login');

        $response->assertRedirect('countDown');
    }

    public function test_countdown_path_as_guest()
    {
        $response = $this->get('/countDown');
        $response->assertRedirect('login');
    }

    public function test_countdown_path_as_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/countDown');
        $response->assertStatus(200);
    }

    public function test_admin_path_as_guest()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('login');
    }

    public function test_admin_path_as_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(401);
    }

    public function test_admin_path_as_admin()
    {
        $user = User::factory()->create(['role'=>'Admin']);
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
    }
}

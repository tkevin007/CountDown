<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Show;
use Tests\TestCase;

class ShowsTest extends TestCase
{

    use RefreshDatabase;


    public function test_get_all_shows()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->get(route('shows.index'));
        $response->assertStatus(200);
    }

    public function test_adding_new_shows()
    {
        $user = User::factory()->create();

        $beforeCount= Show::all()->count();
        $this->actingAs($user)->post(route('shows.store'),["show_id"=>"84958"]);

        $this->assertDatabaseCount('shows', $beforeCount+1);
    }


    public function test_removing_shows()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('shows.store'),["show_id"=>"84958"]);

        $show = Show::find(1);
        $this->actingAs($user)->delete(route('shows.destroy',1));

        $this->assertSoftDeleted($show);
    }

    public function test_editing_shows()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('shows.store'),["show_id"=>"84958"]);

        $show = Show::find(1);

        $data = [
            'season' => 1,
            'episode' => 0,
        ];

        $this->actingAs($user)->json('PUT', route('shows.update', 1), $data);

        $this->assertDatabaseHas('shows', [
            'current_season' =>1,
            'current_episode'=>1,
        ]);
    }

}

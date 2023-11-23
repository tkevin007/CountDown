<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_new_message()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->post(route('report.store'),['message'=>"this is a message",'type'=>"Message"]);

        $this->assertDatabaseCount('reports', 1);
    }

    public function test_modify_unauthorized()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->post(route('report.store'),['message'=>"this is a message",'type'=>"Report"]);
        $report=Report::find(1);

        $response= $this->actingAs($user)->put(route('report.update',$report));
        $response->assertStatus(401);
/*
        $this->assertDatabaseHas('reports', [
            'status' =>'Fixed',
        ]);*/
    }

    public function test_mark_as_fixed()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->post(route('report.store'),['message'=>"this is a message",'type'=>"Report"]);
        $report=Report::find(1);

        $user->role="Admin";
        $response= $this->actingAs($user)->put(route('report.update',$report));

        $this->assertDatabaseHas('reports', [
            'status' =>'Fixed',
        ]);
    }

    public function test_mark_as_unfixed()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->post(route('report.store'),['message'=>"this is a message",'type'=>"Report"]);
        $report=Report::find(1);

        $user->role="Admin";
        $response= $this->actingAs($user)->put(route('report.update',$report));
        $response= $this->actingAs($user)->put(route('report.update',$report));

        $this->assertDatabaseHas('reports', [
            'status' =>'Unfixed',
        ]);
    }

    public function test_mark_as_read()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->post(route('report.store'),['message'=>"this is a message",'type'=>"Message"]);
        $report=Report::find(1);

        $user->role="Admin";
        $response= $this->actingAs($user)->put(route('report.update',$report));

        $this->assertDatabaseHas('reports', [
            'read' =>1,
        ]);
    }

    public function test_mark_as_unread()
    {
        $user = User::factory()->create();
        $response= $this->actingAs($user)->post(route('report.store'),['message'=>"this is a message",'type'=>"Message"]);
        $report=Report::find(1);

        $user->role="Admin";
        $response= $this->actingAs($user)->put(route('report.update',$report));
        $response= $this->actingAs($user)->put(route('report.update',$report));

        $this->assertDatabaseHas('reports', [
            'read' =>0,
        ]);
    }
}

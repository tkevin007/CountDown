<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $random = User::All()->random(rand(6,10))->pluck('id');


        foreach ($random as $userid) {

            Report::factory()->create([
                'user_id' => $userid,
            ]);
        }
        $random = User::All()->random(rand(6,10))->pluck('id');

        foreach ($random as $userid) {

            Report::factory()->create([
                'user_id' => $userid,
            ]);
        }
    }
}

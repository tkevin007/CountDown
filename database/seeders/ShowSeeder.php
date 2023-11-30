<?php

namespace Database\Seeders;

use App\TMDB_api;
use Illuminate\Database\Seeder;
use App\Models\Show;
use App\Models\User;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trendingShowIds = array_column(TMDB_api::getTVTrendingWeek()->results,'id');
        $users = User::All();

        foreach ($users as $u){
            $userRandShowIds = array_rand($trendingShowIds,rand(2,5));

            foreach($userRandShowIds as $showId){
                $show = TMDB_api::getShowDetailsById($trendingShowIds[$showId]);

                $hasSpecials = $show->seasons[0]->season_number==0;

                $seasonCount=0;
                if($hasSpecials)
                $seasonCount=sizeof($show->seasons)-1;
                else
                $seasonCount=sizeof($show->seasons);

                $current_season=rand(1,$seasonCount);

                $current_episode=0;
                $season = TMDB_api::getSeasonDetails($show->id,$current_season);
                $current_episode = $season->episodes[rand(0,count($season->episodes)-1)]->episode_number;


                Show::factory()->create([
                    'status' => $show->status,
                    'user_id' => $u->id,
                    'TMDB_show_id' => $show->id,
                    'current_season' => $current_season,
                    'current_episode' =>$current_episode,
                    'show_name' =>$show->name,
                ]);
            }
        }

    }
}

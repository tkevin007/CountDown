<?php

namespace Database\Seeders;

use App\Models\Show;
use App\TMDB_api;
use Illuminate\Database\Seeder;
use App\Models\Rating;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shows = Show::All();

        foreach ($shows as $show){

            $TMDB_show_id = $show->TMDB_show_id;
            $current_season = $show->current_season;
            $current_episode = $show->current_episode;

            $SeasonFirstEpisode= TMDB_api::getSeasonDetails($TMDB_show_id,$current_season)->episodes[0]->episode_number;

            $limit=5;
            $loop=$limit;
            while($current_episode-$limit+$loop-1>=$SeasonFirstEpisode && $loop>0){

                $episode = TMDB_api::getEpisodeDeatils($TMDB_show_id,$current_season,$current_episode-$limit+$loop-1);

                $name="";
                if(property_exists($episode,'name'))
                $name=$episode->name;

                $overview="";
                if(property_exists($episode,'overview'))
                $overview=$episode->overview;

                $still_path="";
                if(property_exists($episode,'still_path'))
                $still_path=$episode->still_path;

                Rating::factory()->create([
                    'TMDB_show_id' => $TMDB_show_id,
                    'season_number' => $current_season,
                    'episode_number'=> $current_episode-$limit+$loop-1,
                    'episode_name' => $name,
                    'episode_desc' => $overview,
                    'episode_still_path'=> $still_path,
                    'user_id'=> $show->user_id,
                    'show_id' => $show->id,
                    'show_name' => $show->show_name,
                    'user_rating'=> rand(1,10),
                ]);

                $loop--;
            }
        }
    }
}

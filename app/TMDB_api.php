<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\Http;

class TMDB_api{

    /**
    * Gets the trending TV shows of the last day
    * @return \stdClass
    */
    public static function getTVTrendingDay(){
        try{
            $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/trending/tv/day?api_key=68d09c09006fb6d1e72e12874f2f6377');
            if ($response->successful()) {
                // Request was successful
                return $response->object();
            } else {
                $statusCode = $response->status();
                abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
            }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }

    /**
     *  Gets the trending TV shows of the last week
     *   @return \stdClass
     */
    public static function getTVTrendingWeek(){
        try{
        $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/trending/tv/week?api_key=68d09c09006fb6d1e72e12874f2f6377');
        if ($response->successful()) {
            // Request was successful
            return $response->object();
        } else {
            $statusCode = $response->status();
            abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
        }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }

    /**
     * Gets detailed information about a tv show
     * @param int $show_id - Integer, the Id of the searched show
     * @return \stdClass
    */
    public static function getShowDetailsById($show_id){
        try{
        $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/tv/'.$show_id.'?api_key=68d09c09006fb6d1e72e12874f2f6377');
        if ($response->successful()) {
            // Request was successful
            return $response->object();
        } else {
            $statusCode = $response->status();
            abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
        }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }

    /**
     *  Gets detailed information about a sepecific season
     *  @param int $show_id - Integer, the ID of the searched show
     *  @param int $season_number - Integer, the number of the season you want details on
     *  @return \stdClass
     */
    public static function getSeasonDetails($show_id,$season_number){
        try{
        $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/tv/'.$show_id.'/season/'.$season_number.'?api_key=68d09c09006fb6d1e72e12874f2f6377');
        if ($response->successful()) {
            // Request was successful
            return $response->object();
        } else {
            $statusCode = $response->status();
            abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
        }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }

    /**
     * Gets detailed information about a sepecific episode
     * @param int $show_id - Integer, the ID of the searched show
     * @param int $season_number - Integer, the number of the season the episode you want is in
     * @param int $episode_number - Integer, the number of the episode you want deatils about
     * @return \stdClass
     */
    public static function getEpisodeDeatils($show_id,$season_number,$episode_number){
        try{
        $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/tv/'.$show_id.'/season/'.$season_number.'/episode/'.$episode_number.'?api_key=68d09c09006fb6d1e72e12874f2f6377');
        if ($response->successful()) {
            // Request was successful
            return $response->object();
        } else {
            $statusCode = $response->status();
            abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
        }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }

    /**
     * Gets search results about a given search term
     * @param int $searchTerm - String, the show name's substring you are looking for
     * @param int $page - Integer, if there are multiple pages of results for the searchterm you can specify which page you want to get back
     * @return \stdClass
     */
    public static function getSearch($searchTerm, $page){
        try{
        $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/search/tv?query='.$searchTerm.'&page='.$page.'&api_key=68d09c09006fb6d1e72e12874f2f6377');
        if ($response->successful()) {
            // Request was successful
            return $response->object();
        } else {
            $statusCode = $response->status();
            abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
        }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }

    /**
     * Gets images from the given show
     * @param int $show_id - Integer, the id of the show you want pictures about
     * @return \stdClass
     */
    public static function getShowImages($show_id){
        try{
        $response = Http::withOptions(['verify' => false])->get('https://api.themoviedb.org/3/tv/'.$show_id.'/images?api_key=68d09c09006fb6d1e72e12874f2f6377');
        if ($response->successful()) {
            // Request was successful
            return $response->object();
        } else {
            $statusCode = $response->status();
            abort(500, "TMDB API request failed with status code: $statusCode, please refresh the page or try again later");
        }
        }catch(Exception $e){
            abort(500, "TMDB API request failed, please refresh the page or try again later");
        }
    }


}

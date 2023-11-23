<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\Http;

class TMDB_api{


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

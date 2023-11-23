<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\UserSeeder;
use Database\Seeders\FollowSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
        UserSeeder::class,
        FollowSeeder::class,
        ShowSeeder::class,
        RatingSeeder::class,
        ReportSeeder::class,
        ]);
    }
}

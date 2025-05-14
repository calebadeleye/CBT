<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Leaderboard;
use Illuminate\Support\Carbon;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = range(1, 15); // Simulate 15 users
        $entries = [];

        foreach ($users as $userId) {
            $entriesCount = rand(1, 3); // Each user can have 1-3 scores

            for ($i = 0; $i < $entriesCount; $i++) {
                $entries[] = [
                    'user_id' => $userId,
                    'score' => rand(50, 100),
                    'created_at' => Carbon::create(2025, 4, rand(1, 30), rand(0, 23), rand(0, 59), rand(0, 59)),
                    'updated_at' => now(),
                ];
            }
        }

        Leaderboard::insert($entries);
    }

}


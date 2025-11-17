<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyLeaderboardSeeder extends Seeder
{
    public function run()
    {
        // List of names
        $names = [
            'Sola','Adam','Bunmi','Kolawole','Gabrile','Abdulganiu','Jaafar','Sadiku',
            'Imoleseun','James','Fatai','Wole','Fela','Femi','Gideon','Ruth','Wheed',
            'Sandra','Jude','Ololade','Omolayo','Omote','Jadesola','Koforola','Chidinma',
            'Ezechukwu','Arinola','Osaze','Odemwinge','Moyinoluwa','Jamiu','Collins','Sade',
            'Goodluck','Seyi','Sola','Wale','Chris','Adebowale','Iyasele','David','Gafaar',
            'Oriade','Oriowo','Samuel','Pitan','Paul','Tope','Rasheedat'
        ];

        $userIds = [];

        for ($i = 0; $i < 100; $i++) {

            // FIRST + LAST name from same list
            $firstName = $names[array_rand($names)];
            $lastName  = $names[array_rand($names)];

            $fullName = $firstName . ' ' . $lastName;

            $email = strtolower($firstName.$lastName.$i.'@dummy.test');

            // Insert user
            $userId = DB::table('users')->insertGetId([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('password'),
                'is_dummy' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Save ID for later use
            $userIds[] = $userId;

            // Insert leaderboard score
            DB::table('leaderboards')->insert([
                'user_id' => $userId,
                'score' => rand(100, 300),
                'is_dummy' => 1,
                'created_at' => now()->subMonths(rand(0, 12)),
                'updated_at' => now(),
            ]);
        }

        // Create an additional 100 leaderboard records
        for ($i = 0; $i < 100; $i++) {
            DB::table('leaderboards')->insert([
                'user_id' => $userIds[array_rand($userIds)], // now safe
                'score' => rand(100, 300),
                'is_dummy' => 1,
                'created_at' => Carbon::now()->subDays(rand(0, 90)),
                'updated_at' => now(),
            ]);
        }
    }
}

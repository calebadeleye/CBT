<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use Illuminate\Support\Carbon;
class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [];

        for ($i = 0; $i < 50; $i++) {
            $records[] = [
                'user_id' => rand(1, 15), // Adjust range based on your users
                'amount' => 900.00,
                'type' => 'CREDIT',
                'created_at' => Carbon::create(2025, 4, rand(1, 30), rand(0, 23), rand(0, 59), rand(0, 59)),
                'updated_at' => now(),
            ];
        }

        Wallet::insert($records);

   }
}

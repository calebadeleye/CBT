<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $default_admin = Admin::where('email', 'admin@utme.com.ng')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Admin::create([
                'name' => 'Admin',
                'email' =>'admin@utme.com.ng',
                'password' =>  Hash::make('@3487Naitalk!'),
            ]);
        }

    }
}

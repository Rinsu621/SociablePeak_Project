<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 users
        User::factory()->count(10)->create()->each(function ($user) {
            $userDetail = \App\Models\UserDetail::factory()->make();
            $userDetail->user_id = $user->id;
            $userDetail->save();
        });;
    }
}


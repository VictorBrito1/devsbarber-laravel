<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 2; $i++) {
            $user = new User();
            $user->password = Hash::make('password');
            $user->email = "test$i@test.com";
            $user->name = "Test $i";
            $user->save();
        }
    }
}

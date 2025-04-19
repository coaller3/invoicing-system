<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('1234'),
                'role' => 'ADMIN',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'User A',
                'email' => 'userA@mail.com',
                'password' => bcrypt('1234'),
                'role' => 'USER',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'User B',
                'email' => 'userB@mail.com',
                'password' => bcrypt('1234'),
                'role' => 'USER',
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($user as $item) {
            User::create($item);
        }

    }
}

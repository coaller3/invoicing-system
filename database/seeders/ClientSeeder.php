<?php

namespace Database\Seeders;

use App\Models\Client;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $client = [
            [
                'user_id' => 2,
                'name' => 'Client A',
                'email' => 'clientA@mail.com',
                'phone' => '1234567890',
                'company' => 'Company A',
                'address' => 'Address A',
                'status' => 'ACTIVE',
            ],
            [
                'user_id' => 2,
                'name' => 'Client A2',
                'email' => 'clientA2@mail.com',
                'phone' => '0123456789',
                'company' => 'Company A2',
                'address' => 'Address A2',
                'status' => 'ACTIVE',
            ],
            [
                'user_id' => 3,
                'name' => 'Client B',
                'email' => 'clientB@mail.com',
                'phone' => '1234567890',
                'company' => 'Company B',
                'address' => 'Address B',
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($client as $item) {
            Client::create($item);
        }
    }
}

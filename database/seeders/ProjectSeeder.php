<?php

namespace Database\Seeders;

use App\Models\Project;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $projects = [
            // Client A's project (user_id: 2, client_id: 1)
            [
                'client_id' => 1,
                'name' => 'Website Development A',
                'description' => 'E-commerce website development',
                'rate' => 1300.00,
                'duration' => 30,
            ],

            // Client A2's projects (user_id: 2, client_id: 2)
            [
                'client_id' => 2,
                'name' => 'Mobile App Development A2',
                'description' => 'iOS and Android app development',
                'rate' => 2000.00,
                'duration' => 60,
            ],
            [
                'client_id' => 2,
                'name' => 'SEO Optimization A2',
                'description' => 'Website SEO improvement project',
                'rate' => 520.50,
                'duration' => 15,
            ],
            [
                'client_id' => 2,
                'name' => 'Digital Marketing Campaign A2',
                'description' => 'Social media marketing campaign',
                'rate' => 835.00,
                'duration' => 45,
            ],

            // Client B's projects (user_id: 3, client_id: 3)
            [
                'client_id' => 3,
                'name' => 'Database Migration B',
                'description' => 'Legacy system database migration',
                'rate' => 940.00,
                'duration' => 20,
            ],
            [
                'client_id' => 3,
                'name' => 'System Integration B',
                'description' => 'Third-party API integration',
                'rate' => 1200.00,
                'duration' => 25,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}

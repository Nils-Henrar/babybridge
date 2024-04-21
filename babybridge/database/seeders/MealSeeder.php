<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the meals table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('meals')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the meals table

        $meals = [
            [
                'type' => 'fruit',
            ],
            [
                'type' => 'vegetable',
            ],
            [
                'type' => 'feeding bottle',
            ],
        ];

        // Insert the data in the meals table

        DB::table('meals')->insert($meals);
    }
}

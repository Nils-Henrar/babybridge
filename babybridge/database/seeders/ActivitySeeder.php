<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the activities table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the activities table

        $activities = [

            [
                'description' => 'Peinture',
            ],

            [
                'description' => 'Pâte à modeler',
            ],

            [
                'description' => 'Jeux de construction',
            ],

            [
                'description' => 'Lecture',
            ],

            [
                'description' => 'Musique',
            ],

            [
                'description' => 'Sortie dans le jardin',
            ],

            [
                'description' => 'Puzzle',
            ],

            [
                'description' => 'Dessin',
            ],

        ];

        // Insert the data in the activities table

        DB::table('activities')->insert($activities);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the photos table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('photos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $photos = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'description' => 'Hugo en train de peindre',
                'taken_at' => '2024-03-22 11:00:00',
                'path' => 'hugo_peinture.png',
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'description' => 'Léa aime la construction',
                'taken_at' => '2024-03-22 11:00:00',
                'path' => 'lea_construction.jpg',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'description' => 'Lucas joue avec les cubes',
                'taken_at' => '2024-03-22 11:00:00',
                'path' => 'lucas_cubes.jpg',
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'description' => 'Jules et son doudou',
                'taken_at' => '2024-03-22 11:00:00',
                'path' => 'jules_doudou.jpg',
            ],

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'description' => 'Hugo et son doudou',
                'taken_at' => '2024-03-22 11:00:00',
                'path' => 'hugo_doudou.jpg',
            ],

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'description' => 'Hugo et son doudou',
                'taken_at' => '2024-03-22 10:30:00',
                'path' => 'hugo_fatigue.jpeg',
            ],

        ];

        foreach ($photos as &$data) {

            $child = DB::table('children')
                ->where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            $data['child_id'] = $child->id;

            unset($data['child_firstname']);
            unset($data['child_lastname']);
        }

        unset($data);

        // Insert the data in the photos table

        DB::table('photos')->insert($photos);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // empty the children table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Child::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the children table (0 et 3 ans) nous sommes en 2024

        $children = [
            [
                'firstname' => 'Léa',
                'lastname' => 'Dupont',
                'gender' => 'F',
                'birthdate' => '2021-08-12',
            ],

            [
                'firstname' => 'Lucas',
                'lastname' => 'Durand',
                'gender' => 'M',
                'birthdate' => '2022-02-22',
            ],

            [
                'firstname' => 'Emma',
                'lastname' => 'Lefevre',
                'gender' => 'F',
                'birthdate' => '2022-03-22',
            ],

            [
                'firstname' => 'Jules',
                'lastname' => 'Leroy',
                'gender' => 'M',
                'birthdate' => '2022-05-22',

            ],

            [
                'firstname' => 'Louise',
                'lastname' => 'Moreau',
                'gender' => 'F',
                'birthdate' => '2024-03-22',
            ],

            [
                'firstname' => 'Hugo',
                'lastname' => 'Lemoine',
                'gender' => 'M',
                'birthdate' => '2021-02-14',
            ],

            [
                'firstname' => 'Chloé',
                'lastname' => 'Girard',
                'gender' => 'F',
                'birthdate' => '2023-01-25',
            ],

            [
                'firstname' => 'Arthur',
                'lastname' => 'Roux',
                'gender' => 'M',
                'birthdate' => '2023-03-22',
            ],

        ];

        // Insert the data in the children table
        DB::table('children')->insert($children);
    }
}

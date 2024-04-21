<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutorChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tutor_child')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $tutorChildren = [

            [
                'user_login' => 'marc23',
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
            ],

            [
                'user_login' => 'julie56',
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
            ],

            [
                'user_login' => 'paul78',
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
            ],

            [
                'user_login' => 'sophie90',
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
            ],

            [
                'user_login' => 'thomas12',
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
            ],

            [
                'user_login' => 'lucie34',
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
            ],

            [
                'user_login' => 'lucie34',
                'child_firstname' => 'Arthur',
                'child_lastname' => 'Roux',
            ],
        ];

        // Insert the data in the table

        foreach ($tutorChildren as &$data) {
            $user = DB::table('users')->where('login', $data['user_login'])->first();

            $child = DB::table('children')->where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            unset($data['user_login']);
            unset($data['child_firstname']);
            unset($data['child_lastname']);

            $data['user_id'] = $user->id;
            $data['child_id'] = $child->id_child;
        }

        unset($data);

        DB::table('tutor_child')->insert($tutorChildren);
    }
}

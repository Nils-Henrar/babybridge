<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the naps table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('naps')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Define the data to seed the naps table 

        $naps = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'start_time' => '2024-03-22 10:00:00',
                'end_time' => '2024-03-22 11:00:00',
                'quality' => 'good',
                'notes' => null,

            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'start_time' => '2024-03-22 10:00:00',
                'end_time' => '2024-03-22 12:00:00',
                'quality' => 'good',
                'notes' => 'Léa a dormi 1h',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'start_time' => '2024-03-22 09:00:00',
                'end_time' => '2024-03-22 09:30:00',
                'quality' => 'average',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'start_time' => '2024-03-22 10:00:00',
                'end_time' => '2024-03-22 11:00:00',
                'quality' => 'bad',
                'notes' => 'Jules a refusé de dormir',

            ],

            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'start_time' => '2024-03-22 09:00:00',
                'end_time' => '2024-03-22 10:30:00',
                'quality' => 'bad',
                'notes' => 'Louise n\'a pas dormi du tout',
            ],

            [
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'start_time' => '2024-03-22 10:00:00',
                'end_time' => '2024-03-22 11:00:00',
                'quality' => 'good',
                'notes' => null,
            ],


        ];

        // Insert the data in the naps table

        foreach ($naps as &$data) {
            $child = DB::table('children')
                ->where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            if ($child) {

                $data['child_id'] = $child->id;
                unset($data['child_firstname']);
                unset($data['child_lastname']);
            }
        }

        unset($data);

        DB::table('naps')->insert($naps);
    }
}

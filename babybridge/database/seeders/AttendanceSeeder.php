<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Child;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Empty the attendances table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendances')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Define the data to seed the attendances table

        $attendances = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '08:00',
                'departure_time' => '17:00',
                'notes' => 'Hugo a apporté son doudou',

            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '08:00',
                'departure_time' => '17:00',
                'notes' => 'Léa a apporté son doudou',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '08:00',
                'departure_time' => '17:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '08:00',
                'departure_time' => '17:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '08:00',
                'departure_time' => '17:00',
                'notes' => 'Emma a apporté son doudou',
            ],



            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '08:00',
                'departure_time' => '17:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'attendance_date' => '2024-03-22',
                'arrival_time' => '10:30',
                'departure_time' => '17:00',
                'notes' => 'Léa sera en retard',
            ],



        ];

        // Insert the data into the attendances table


        foreach ($attendances as &$data) {
            $child = Child::where('firstname', trim($data['child_firstname']))
                ->where('lastname', trim($data['child_lastname']))
                ->first();


            unset($data['child_firstname']);
            unset($data['child_lastname']);

            $data['child_id'] = $child->id;
        }

        unset($data);
        DB::table('attendances')->insert($attendances);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Child;
use App\Models\Activity;

class ActivityChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the child_activities table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activity_child')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the child_activities table

        $child_activities = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'activity_description' => 'Peinture',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'activity_description' => 'Peinture',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'activity_description' => 'Peinture',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'activity_description' => 'Pâte à modeler',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'activity_description' => 'Jeux de construction',
                //datetime
                'performed_at' => '2024-03-18 10:30:00',
            ],

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'activity_description' => 'Jeux de construction',
                //datetime
                'performed_at' => '2023-12-22 11:00:00',
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'activity_description' => 'Jeux de construction',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'activity_description' => 'Pâte à modeler',
                //datetime
                'performed_at' => '2024-03-22 14:00:00',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'activity_description' => 'Lecture',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'activity_description' => 'Lecture',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'activity_description' => 'Lecture',
                //datetime
                'performed_at' => '2024-03-22 11:00:00',
            ],

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'activity_description' => 'Musique',
                //datetime
                'performed_at' => '2024-03-22 14:00:00',
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'activity_description' => 'Musique',
                //datetime
                'performed_at' => '2024-03-22 14:00:00',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'activity_description' => 'Musique',
                //datetime
                'performed_at' => '2024-03-22 14:00:00',
            ],

            [
                'child_firstname' => 'Astrid',
                'child_lastname' => 'Lemoine',
                'activity_description' => 'Musique',
                //datetime
                'performed_at' => '2024-03-22 16:00:00',
            ],

            [
                'child_firstname' => 'Astrid',
                'child_lastname' => 'Lemoine',
                'activity_description' => 'Peinture',
                //datetime
                'performed_at' => '2024-03-22 12:00:00',
            ]

        ];

        foreach ($child_activities as &$data) {
            $data['performed_at'] = date('Y-m-d H:i:s', strtotime($data['performed_at']));

            $child = Child::where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            $activity = Activity::where('description', $data['activity_description'])
                ->first();

            $data['child_id'] = $child->id;
            $data['activity_id'] = $activity->id;

            unset($data['child_firstname']);
            unset($data['child_lastname']);
            unset($data['activity_description']);
        }

        unset($data);

        // Insert the data in the child_activities table

        DB::table('activity_child')->insert($child_activities);
    }
}

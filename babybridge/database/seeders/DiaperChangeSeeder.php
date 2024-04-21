<?php

namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiaperChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the diaper_changes table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('diaper_changes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the diaper_changes table


        $diaper_changes = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'poop_consistency' => 'normal',
                'happened_at' => '2024-03-22 09:10:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'poop_consistency' => 'soft',
                'happened_at' => '2024-03-22 11:00:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'poop_consistency' => 'normal',
                'happened_at' => '2024-03-22 10:30:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'poop_consistency' => 'normal',
                'happened_at' => '2024-03-22 11:10:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'poop_consistency' => 'soft',
                'happened_at' => '2024-03-22 09:20:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'poop_consistency' => 'watery',
                'happened_at' => '2024-03-22 10:30:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Chloé',
                'child_lastname' => 'Girard',
                'poop_consistency' => 'normal',
                'happened_at' => '2024-03-22 11:00:00',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Arthur',
                'child_lastname' => 'Roux',
                'poop_consistency' => 'normal',
                'happened_at' => '2024-03-22 11:00:00',
                'notes' => null,
            ],

        ];

        // Seed the diaper_changes table

        foreach ($diaper_changes as &$data) {
            $child = Child::where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            unset($data['child_firstname']);
            unset($data['child_lastname']);

            $data['child_id'] = $child->id_child;
            $data['happened_at'] = date('Y-m-d H:i:s', strtotime($data['happened_at']));
        }

        DB::table('diaper_changes')->insert($diaper_changes);
    }
}

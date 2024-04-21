<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('child_section')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $childSections = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'section_slug' => 'les-lilas',
                'from' => '2023-03-22 10:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'section_slug' => 'les-roses',
                'from' => '2023-03-22 10:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'section_slug' => 'les-tournesols',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'section_slug' => 'Les-coquelicots',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'section_slug' => 'Les-bleuets',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'section_slug' => 'Les-jonquilles',
                'from' => '2023-03-22 16:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Chloé',
                'child_lastname' => 'Girard',
                'section_slug' => 'Les-pâquerettes',
                'from' => '2023-03-22 16:00:00',
                'to' => null,
            ],

            [
                'child_firstname' => 'Arthur',
                'child_lastname' => 'Roux',
                'section_slug' => 'Les-pâquerettes',
                'from' => '2023-03-22 16:00:00',
                'to' => null,
            ],

        ];

        // Insert the data in the table

        foreach ($childSections as &$data) {
            $child = DB::table('children')
                ->where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            $section = DB::table('sections')
                ->where('slug', $data['section_slug'])
                ->first();

            $data['child_id'] = $child->id_child;
            $data['section_id'] = $section->id_section;

            unset($data['child_firstname']);
            unset($data['child_lastname']);
            unset($data['section_slug']);
        }

        unset($data);

        DB::table('child_section')->insert($childSections);
    }
}

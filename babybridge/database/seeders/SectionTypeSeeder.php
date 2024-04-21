<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('section_type')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $sectionTypes = [

            [
                'section_slug' => 'les-lilas',
                'type_name' => 'petits',
                'from' => '2023-03-22 10:00:00',
                'to' => null,
            ],

            [
                'section_slug' => 'les-roses',
                'type_name' => 'petits',
                'from' => '2023-03-22 10:00:00',
                'to' => null,
            ],

            [
                'section_slug' => 'les-tournesols',
                'type_name' => 'moyens',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'section_slug' => 'les-coquelicots',
                'type_name' => 'moyens',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'section_slug' => 'les-pensées',
                'type_name' => 'grands',
                'from' => '2024-03-22 13:00:00',
                'to' => null,
            ],

            [
                'section_slug' => 'les-pensées',
                'type_name' => 'petits',
                'from' => '2021-03-22 13:00:00',
                'to' => '2024-03-22 13:00:00',
            ],

            [
                'section_slug' => 'les-bleuets',
                'type_name' => 'grands',
                'from' => '2022-03-22 13:00:00',
                'to' => '2024-03-22 13:00:00',
            ],
            [
                'section_slug' => 'les-bleuets',
                'type_name' => 'petits',
                'from' => '2024-03-22 13:00:00',
                'to' => null,
            ],



        ];

        // Insert the data into the table

        foreach ($sectionTypes as &$data) {
            $section = DB::table('sections')->where('slug', $data['section_slug'])->first();
            $type = DB::table('types')->where('name', $data['type_name'])->first();


            $data['section_id'] = $section->id_section;
            $data['type_id'] = $type->id_type;

            unset($data['section_slug']);
            unset($data['type_name']);

            $data['from'] = date('Y-m-d H:i:s', strtotime($data['from']));

            if ($data['to'] !== null) {
                $data['to'] = date('Y-m-d H:i:s', strtotime($data['to']));
            }
        }

        unset($data);

        DB::table('section_type')->insert($sectionTypes);
    }
}

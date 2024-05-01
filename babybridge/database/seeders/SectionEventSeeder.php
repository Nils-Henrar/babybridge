<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('section_event')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $sectionEvents = [
            [
                'slug_section' => 'les-lilas',
                'slug_event' => 'la-ferme-en-folie',
            ],

            [
                'slug_section' => 'les-roses',
                'slug_event' => 'spectacle-de-magie',
            ],

            [
                'slug_section' => 'les-tulipes',
                'slug_event' => 'spectacle-de-marionnettes',
            ],

            [
                'slug_section' => 'les-lilas',
                'slug_event' => 'bienvenue-au-cirque',
            ],

            [
                'slug_section' => 'les-roses',
                'slug_event' => 'natation-pour-les-petits',
            ],

            [
                'slug_section' => 'les-tulipes',
                'slug_event' => 'la-ferme-en-folie',
            ],

            [
                'slug_section' => 'les-lilas',
                'slug_event' => 'spectacle-de-magie',
            ],

            [
                'slug_section' => 'les-roses',
                'slug_event' => 'spectacle-de-marionnettes',
            ],

            [
                'slug_section' => 'les-tulipes',
                'slug_event' => 'bienvenue-au-cirque',
            ],

            [
                'slug_section' => 'les-lilas',
                'slug_event' => 'natation-pour-les-petits',
            ],

            [
                'slug_section' => 'les-roses',
                'slug_event' => 'la-ferme-en-folie',
            ],

            [
                'slug_section' => 'les-tulipes',
                'slug_event' => 'spectacle-de-magie',
            ],

            [
                'slug_section' => 'les-lilas',
                'slug_event' => 'spectacle-de-marionnettes',
            ],

            [
                'slug_section' => 'les-roses',
                'slug_event' => 'bienvenue-au-cirque',
            ],

            [
                'slug_section' => 'les-tulipes',
                'slug_event' => 'natation-pour-les-petits',
            ],
        ];

        // Insert the data in the table

        foreach ($sectionEvents as &$data) {
            $section = DB::table('sections')->where('slug', $data['slug_section'])->first();
            $event = DB::table('events')->where('slug', $data['slug_event'])->first();




            $data['section_id'] = $section->id_section;
            $data['event_id'] = $event->id_event;

            unset($data['slug_section']);
            unset($data['slug_event']);
        }

        unset($data);

        DB::table('section_event')->insert($sectionEvents);
    }
}

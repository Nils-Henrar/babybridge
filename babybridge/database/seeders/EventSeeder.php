<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table 

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('events')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $events = [
            [
                'title' => 'La ferme en folie',
                'slug' => null,
                'schedule' => '2024-06-12 14:00:00',
                'description' => 'La ferme débarque à la crèche, les enfants pourront découvrir les animaux de la ferme et participer à des ateliers de découverte.',
            ],

            [
                'title' => 'Spectacle de magie',
                'slug' => null,
                'schedule' => '2024-09-16 14:00:00',
                'description' => 'Un magicien viendra émerveiller les enfants avec des tours de magie',
            ],

            [
                'title' => 'Spectacle de marionnettes',
                'slug' => null,
                'schedule' => '2024-12-12 14:00:00',
                'description' => 'Un spectacle de marionnettes sera proposé aux enfants.',
            ],

            [
                'title' => 'Bienvenue au cirque',
                'slug' => null,
                'schedule' => '2024-07-08 12:00:00',
                'description' => 'Un spectacle de cirque sera proposé aux enfants, avec des clowns, des acrobates et des animaux.',
            ],

            [
                'title' => 'Natation pour les petits',
                'slug' => null,
                'schedule' => '2024-08-12 14:00:00',
                'description' => 'Des leçons de natation spécialement conçues pour les bébés et les tout-petits, avec des activités aquatiques adaptées à leur niveau de développement.',
            ]
        ];

        // Insert the data in the table

        foreach ($events as &$event) {
            $event['slug'] = Str::slug($event['title']);
        }

        DB::table('events')->insert($events);
    }
}

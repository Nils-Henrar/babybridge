<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the sections table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // définir les sections de la crèche

        $sections = [
            [
                'name' => 'Les lilas',
                'slug' => null,
                'created_at' => '2021-08-12 15:55:52'
            ],

            [
                'name' => 'Les roses',
                'slug' => null,
                'created_at' => '2022-02-22 15:55:52'
            ],

            [
                'name' => 'Les tournesols',
                'slug' => null,
                'created_at' => '2022-03-22 15:55:52'
            ],

            [
                'name' => 'Les coquelicots',
                'slug' => null,
                'created_at' => '2022-05-22 15:55:52'
            ],

            [
                'name' => 'Les bleuets',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les jonquilles',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les pâquerettes',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les pensées',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les myosotis',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les orchidées',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les hortensias',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les mimosas',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les camélias',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les azalées',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les glycines',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les hibiscus',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],

            [
                'name' => 'Les pivoines',
                'slug' => null,
                'created_at' => '2024-03-22 15:55:52'
            ],
        ];


        foreach ($sections as &$data) {
            $data['slug'] = Str::slug($data['name'], '-');
        }


        // insérer les données dans la table sections



        DB::table('sections')->insert($sections);
    }
}

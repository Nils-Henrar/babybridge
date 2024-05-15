<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('section_worker')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $workerSections = [

            [
                'worker_login' => 'geraldine94',
                'section_slug' => 'les-mimosas',
                'from' => '2023-03-22 10:00:00',
                'to' => null,
            ],

            [
                'worker_login' => 'annabelle22',
                'section_slug' => 'les-roses',
                'from' => '2023-03-22 10:00:00',
                'to' => null,
            ],

            [
                'worker_login' => 'pierre56',
                'section_slug' => 'les-tournesols',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'worker_login' => 'Amandine78',
                'section_slug' => 'Les-coquelicots',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

            [
                'worker_login' => 'Aurelie90',
                'section_slug' => 'les-lilas',
                'from' => '2023-03-22 13:00:00',
                'to' => null,
            ],

        ];

        // Insert the data in the table

        foreach ($workerSections as &$data) {
            $user = DB::table('users')->where('login', $data['worker_login'])->first();

            $worker = DB::table('childcare_workers')->where('user_id', $user->id)->first();

            $section = DB::table('sections')->where('slug', $data['section_slug'])->first();

            $data['worker_id'] = $worker->id;
            $data['section_id'] = $section->id;

            unset($data['worker_login']);
            unset($data['section_slug']);
        }

        unset($data);

        DB::table('section_worker')->insert($workerSections);
    }
}

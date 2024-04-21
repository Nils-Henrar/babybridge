<?php

namespace Database\Seeders;

use Dflydev\DotAccessData\Data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('childcare_workers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $workers = [

            [
                'user_login' => 'geraldine94',
            ],

            [
                'user_login' => 'annabelle22',
            ],

            [
                'user_login' => 'pierre56',
            ],

            [
                'user_login' => 'Amandine78',
            ],

            [
                'user_login' => 'Aurelie90',
            ],

        ];

        // Insert the data in the table

        foreach ($workers as &$data) {
            $user = DB::table('users')->where('login', $data['user_login'])->first();

            $data['user_id'] = $user->id;

            unset($data['user_login']);
        }
        unset($data);

        DB::table('childcare_workers')->insert($workers);
    }
}

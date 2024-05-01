<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $roleUsers = [

            [
                'user_login' => 'admin',
                'role' => 'admin',
            ],

            [
                'user_login' => 'geraldine94',
                'role' => 'worker',
            ],

            [
                'user_login' => 'annabelle22',
                'role' => 'worker',
            ],

            [
                'user_login' => 'pierre56',
                'role' => 'worker',
            ],

            [
                'user_login' => 'Amandine78',
                'role' => 'worker',
            ],

            [
                'user_login' => 'Aurelie90',
                'role' => 'worker',
            ],

            [
                'user_login' => 'marc23',
                'role' => 'tutor',
            ],

            [
                'user_login' => 'julie56',
                'role' => 'tutor',
            ],

            [
                'user_login' => 'paul78',
                'role' => 'tutor',
            ],

            [
                'user_login' => 'sophie90',
                'role' => 'tutor',
            ],

            [
                'user_login' => 'thomas12',
                'role' => 'tutor',
            ],

            [
                'user_login' => 'lucie34',
                'role' => 'tutor',
            ],

        ];

        // Insert the data in the table

        foreach ($roleUsers as &$data) {
            $user = DB::table('users')->where('login', $data['user_login'])->first();
            $role = DB::table('roles')->where('role', $data['role'])->first();

            $data['user_id'] = $user->id;
            $data['role_id'] = $role->id;

            unset($data['user_login']);
            unset($data['role']);
        }

        unset($data);

        DB::table('role_user')->insert($roleUsers);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the table

        $users = [

            [
                'login' => 'marc23',
                'password' => 'marc23',
                'firstname' => 'Marc',
                'lastname' => 'Lemoine',
                'email' => 'marc@lemoine.com',
                'phone' => '0601020304',
                'langue' => 'fr',
            ],

            [
                'login' => 'julie56',
                'password' => 'julie56',
                'firstname' => 'Julie',
                'lastname' => 'Dupont',
                'email' => 'julie@dupont.com',
                'phone' => '0475842369',
                'langue' => 'fr',
            ],

            [
                'login' => 'paul78',
                'password' => 'paul78',
                'firstname' => 'Paul',
                'lastname' => 'Durand',
                'email' => 'paul@durand',
                'phone' => '0485963254',
                'langue' => 'fr',
            ],

            [
                'login' => 'sophie90',
                'password' => 'sophie90',
                'firstname' => 'Sophie',
                'lastname' => 'Leroy',
                'email' => 'sophie@leroy.com',
                'langue' => 'fr',
                'phone' => '0478963254',
            ],

            [
                'login' => 'thomas12',
                'password' => 'thomas12',
                'firstname' => 'Thomas',
                'lastname' => 'Moreau',
                'email' => 'thomas@moreau.com',
                'phone' => '0498963254',
                'langue' => 'fr',
            ],

            [
                'login' => 'lucie34',
                'password' => 'lucie34',
                'firstname' => 'Lucie',
                'lastname' => 'Lefevre',
                'email' => 'lucie@lefevre.com',
                'phone' => '0468965284',
                'langue' => 'fr',
            ],

            [
                'login' => 'geraldine94',
                'password' => 'geraldine94',
                'firstname' => 'Géraldine',
                'lastname' => 'Kemp',
                'email' => 'geraldine@kemp.com',
                'phone' => '0486682454',
                'langue' => 'fr',
            ],

            [
                'login' => 'annabelle22',
                'password' => 'annabelle22',
                'firstname' => 'Annabelle',
                'lastname' => 'Vertigo',
                'email' => 'annabelle@vertigo.com',
                'phone' => '0483365684',
                'langue' => 'fr',
            ],

            [
                'login' => 'pierre56',
                'password' => 'pierre56',
                'firstname' => 'Pierre',
                'lastname' => 'Lebrun',
                'email' => 'pierre@lebrun.com',
                'phone' => '0483398884',
                'langue' => 'fr',

            ],

            [
                'login' => 'sylvie78',
                'password' => 'sylvie78',
                'firstname' => 'Sylvie',
                'lastname' => 'Lemoine',
                'email' => 'sylvie@lemoine.com',
                'phone' => '0477842369',
                'langue' => 'fr',
            ],

            [
                'login' => 'pauline90',
                'password' => 'pauline90',
                'firstname' => 'Pauline',
                'lastname' => 'Durand',
                'email' => 'pauline@durand',
                'phone' => '0485553254',
                'langue' => 'fr',
            ],

            [
                'login' => 'amandine78',
                'password' => 'amandine78',
                'firstname' => 'Amandine',
                'lastname' => 'Lareine',
                'email' => 'amandine@lareine.com',
                'phone' => '0496887755',
                'langue' => 'fr',
            ],

            [
                'login' => 'aurelie90',
                'password' => 'aurelie90',
                'firstname' => 'Aurélie',
                'lastname' => 'Tarot',
                'email' => 'aurelie@tarot.com',
                'phone' => '0477968952',
                'langue' => 'fr',
            ],

            [
                'login' => 'sylvain12',
                'password' => 'sylvain12',
                'firstname' => 'Sylvain',
                'lastname' => 'Lefevre',
                'email' => 'sylvain@lefevre.com',
                'phone' => '0489996625',
                'langue' => 'fr',
            ],

        ];

        // Insert the data in the table

        DB::table('users')->insert($users);
    }
}

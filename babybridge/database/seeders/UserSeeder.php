<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'login' => 'admin',
                'password' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'email' => 'admin@admin.com',
                'phone' => null,
                'langue' => 'fr',
                'address' => null,
                'postal_code' => null,
                'city' => null,

            ],

            [
                'login' => 'marc23',
                'password' => 'marc23',
                'firstname' => 'Marc',
                'lastname' => 'Lemoine',
                'email' => 'marc@lemoine.com',
                'phone' => '0601020304',
                'langue' => 'fr',
                'address' => 'Rue de la Paix, 12',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'julie56',
                'password' => 'julie56',
                'firstname' => 'Julie',
                'lastname' => 'Dupont',
                'email' => 'julie@dupont.com',
                'phone' => '0475842369',
                'langue' => 'fr',
                'address' => 'Rue de la Liberté, 56',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'paul78',
                'password' => 'paul78',
                'firstname' => 'Paul',
                'lastname' => 'Durand',
                'email' => 'paul@durand',
                'phone' => '0485963254',
                'langue' => 'fr',
                'address' => 'Rue de la Joie, 78',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'sophie90',
                'password' => 'sophie90',
                'firstname' => 'Sophie',
                'lastname' => 'Leroy',
                'email' => 'sophie@leroy.com',
                'langue' => 'fr',
                'phone' => '0478963254',
                'address' => 'Rue de la Paix, 90',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'thomas12',
                'password' => 'thomas12',
                'firstname' => 'Thomas',
                'lastname' => 'Moreau',
                'email' => 'thomas@moreau.com',
                'phone' => '0498963254',
                'langue' => 'fr',
                'address' => 'Rue de l\'Eté, 12',
                'postal_code' => '1050',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'lucie34',
                'password' => 'lucie34',
                'firstname' => 'Lucie',
                'lastname' => 'Lefevre',
                'email' => 'lucie@lefevre.com',
                'phone' => '0468965284',
                'langue' => 'fr',
                'address' => 'Rue de l\'Hiver, 34',
                'postal_code' => '1050',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'geraldine94',
                'password' => 'geraldine94',
                'firstname' => 'Géraldine',
                'lastname' => 'Kemp',
                'email' => 'geraldine@kemp.com',
                'phone' => '0486682454',
                'langue' => 'fr',
                'address' => 'Rue de l\'Automne, 94',
                'postal_code' => '1050',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'annabelle22',
                'password' => 'annabelle22',
                'firstname' => 'Annabelle',
                'lastname' => 'Vertigo',
                'email' => 'annabelle@vertigo.com',
                'phone' => '0483365684',
                'langue' => 'fr',
                'address' => 'Rue du Printemps, 22',
                'postal_code' => '1050',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'pierre56',
                'password' => 'pierre56',
                'firstname' => 'Pierre',
                'lastname' => 'Lebrun',
                'email' => 'pierre@lebrun.com',
                'phone' => '0483398884',
                'langue' => 'fr',
                'address' => 'Rue de l\'Eglise, 56',
                'postal_code' => '1050',
                'city' => 'Bruxelles',

            ],

            [
                'login' => 'sylvie78',
                'password' => 'sylvie78',
                'firstname' => 'Sylvie',
                'lastname' => 'Lemoine',
                'email' => 'sylvie@lemoine.com',
                'phone' => '0477842369',
                'langue' => 'fr',
                'address' => 'Boulevard de la Liberté, 78',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'pauline90',
                'password' => 'pauline90',
                'firstname' => 'Pauline',
                'lastname' => 'Durand',
                'email' => 'pauline@durand',
                'phone' => '0485553254',
                'langue' => 'fr',
                'address' => 'Rue des Mimosas, 90',
                'postal_code' => '1030',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'amandine78',
                'password' => 'amandine78',
                'firstname' => 'Amandine',
                'lastname' => 'Lareine',
                'email' => 'amandine@lareine.com',
                'phone' => '0496887755',
                'langue' => 'fr',
                'address' => 'Rue des Roses, 78',
                'postal_code' => '1030',
                'city' => 'Bruxelles',
            ],

            [
                'login' => 'aurelie90',
                'password' => 'aurelie90',
                'firstname' => 'Aurélie',
                'lastname' => 'Tarot',
                'email' => 'aurelie@tarot.com',
                'phone' => '0477968952',
                'langue' => 'fr',
                'address' => 'Rue des Lilas, 90',
                'postal_code' => '6000',
                'city' => 'Charleroi',
            ],

            [
                'login' => 'sylvain12',
                'password' => 'sylvain12',
                'firstname' => 'Sylvain',
                'lastname' => 'Lefevre',
                'email' => 'sylvain@lefevre.com',
                'phone' => '0489996625',
                'langue' => 'fr',
                'address' => 'Rue des Coquelicots, 12',
                'postal_code' => '6000',
                'city' => 'Charleroi',
            ],

        ];

        // Insert the data in the table

        foreach ($users as &$user) {
            $user['password'] = Hash::make($user['password']);
        }

        DB::table('users')->insert($users);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty table payments
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $payements = [

            [
                'user_login' => 'marc23',
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'event_slug' => 'la-ferme-en-folie',

            ],

            [
                'user_login' => 'Julie56',
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'event_slug' => 'spectacle-de-magie',
            ],

            [
                'user_login' => 'paul78',
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'event_slug' => 'spectacle-de-marionnettes',
            ],

            [
                'user_login' => 'sophie90',
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'event_slug' => 'bienvenue-au-cirque',
            ],

            [
                'user_login' => 'thomas12',
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'event_slug' => 'natation-pour-les-petits',
            ],

            [
                'user_login' => 'lucie34',
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'event_slug' => 'la-ferme-en-folie',
            ],

            [
                'user_login' => 'marc23',
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'event_slug' => 'spectacle-de-magie',
            ],

            [
                'user_login' => 'Julie56',
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'event_slug' => 'spectacle-de-marionnettes',
            ],

            [
                'user_login' => 'paul78',
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'event_slug' => 'bienvenue-au-cirque',
            ],

            [
                'user_login' => 'sophie90',
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'event_slug' => 'natation-pour-les-petits',
            ],

            [
                'user_login' => 'thomas12',
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'event_slug' => 'la-ferme-en-folie',
            ],

            [
                'user_login' => 'lucie34',
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'event_slug' => 'spectacle-de-magie',
            ],

            [
                'user_login' => 'marc23',
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'event_slug' => 'spectacle-de-marionnettes',
            ],


        ];

        // Insert the data in the table

        foreach ($payements as &$data) {
            $user = DB::table('users')->where('login', $data['user_login'])->first();
            $child = DB::table('children')->where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            $tutorChild = DB::table('tutor_child')->where('user_id', $user->id)
                ->where('child_id', $child->id)
                ->first();


            $event = DB::table('events')->where('slug', $data['event_slug'])->first();

            unset($data['user_login']);
            unset($data['child_firstname']);
            unset($data['child_lastname']);
            unset($data['event_slug']);

            $data['tutor_child_id'] = $tutorChild->id;

            $data['event_id'] = $event->id;
        }

        unset($data);

        DB::table('payments')->insert($payements);
    }
}

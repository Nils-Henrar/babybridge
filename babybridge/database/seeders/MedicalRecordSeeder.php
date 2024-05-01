<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the medical_records table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('medical_records')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        //     public function up(): void
        // {
        //     Schema::create('medical_records', function (Blueprint $table) {
        //         $table->id('id_mrecord');
        //         $table->foreignId('child_id')->references('id_child')->on('children')
        //             ->onUpdate('cascade')
        //             ->onDelete('restrict');
        //         $table->text('description');
        //         $table->timestamp('created_at')->useCurrent();
        //         //updatedId is nullable because it will be null when the record is created
        //         $table->integer('updatedId')->nullable();
        //     });
        // }

        $mRecords = [
            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'description' =>
                'Allergies: pollen, acariens, poils de chat
                Vaccins: DTP, ROR, hépatite B
                Autres: Hugo est asthmatique',
                'created_at' => '2022-03-22 11:00:00',
                'updatedId' => null,
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'description' =>
                'Allergies: aucune,
                Vaccins: hépatite B,
                Autres: Léa est suivie par un orthophoniste',
                'created_at' => '2022-03-22 11:00:00',
                'updatedId' => null,
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'description' =>
                'Allergies: pollen, acariens
                Vaccins: DTP, ROR
                Autres: Lucas est suivi par un psychomotricien',
                'created_at' => '2022-03-22 11:00:00',
                'updatedId' => null,
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'description' =>
                'Allergies: pollen
                Vaccins: DTP, ROR, hépatite B
                Autres: Jules est suivi par un orthophoniste',
                'created_at' => '2022-03-22 11:00:00',
                'updatedId' => null,
            ],

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'description' =>
                'Allergies: pollen, acariens, poils de chat
                Vaccins: DTP, ROR, hépatite B
                Autres: Hugo est asthmatique',
                'created_at' => '2022-03-22 11:00:00',
                'updatedId' => null,
            ],
        ];

        foreach ($mRecords as &$data) {

            $child = DB::table('children')
                ->where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            $data['child_id'] = $child->id;

            unset($data['child_firstname']);
            unset($data['child_lastname']);
        }

        unset($data);

        DB::table('medical_records')->insert($mRecords);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Child;
use App\Models\Meal;

class ChildMealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empty the meals table

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('child_meal')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the data to seed the meals table

        $childMeals = [

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'meal_time' => '2024-03-22 10:00:00',
                'meal_type' => 'feeding bottle',
                'quantity' => 'full',
                'notes' => null,

            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'meal_time' => '2024-03-22 10:00:00',
                'meal_type' => 'feeding bottle',
                'quantity' => 'half',
                'notes' => 'Léa a bu 80 ml',
            ],

            [
                'child_firstname' => 'Lucas',
                'child_lastname' => 'Durand',
                'meal_time' => '2024-03-22 13:00:00',
                'meal_type' => 'vegetable',
                'quantity' => 'full',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Jules',
                'child_lastname' => 'Leroy',
                'meal_time' => '2024-03-22 13:00:00',
                'meal_type' => 'fruit',
                'quantity' => 'half',
                'notes' => null,

            ],

            [
                'child_firstname' => 'Louise',
                'child_lastname' => 'Moreau',
                'meal_time' => '2024-03-22 13:00:00',
                'meal_type' => 'vegetable',
                'quantity' => 'full',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Emma',
                'child_lastname' => 'Lefevre',
                'meal_time' => '2024-03-22 16:00:00',
                'meal_type' => 'fruit',
                'quantity' => 'quarter',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Chloé',
                'child_lastname' => 'Girard',
                'meal_time' => '2024-03-22 16:00:00',
                'meal_type' => 'fruit',
                'quantity' => 'half',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Arthur',
                'child_lastname' => 'Roux',
                'meal_time' => '2024-03-22 16:00:00',
                'meal_type' => 'fruit',
                'quantity' => 'refused',
                'notes' => 'Arthur n\'a pas voulu de fruit probablement à cause de la fièvre',
            ],

            [
                'child_firstname' => 'Léa',
                'child_lastname' => 'Dupont',
                'meal_time' => '2024-03-22 16:00:00',
                'meal_type' => 'fruit',
                'quantity' => 'full',
                'notes' => null,
            ],

            [
                'child_firstname' => 'Hugo',
                'child_lastname' => 'Lemoine',
                'meal_time' => '2024-03-22 16:00:00',
                'meal_type' => 'fruit',
                'quantity' => 'half',
                'notes' => null,
            ],



        ];

        // Insert the data in the child_meals table

        foreach ($childMeals as &$data) {
            $child = Child::where('firstname', $data['child_firstname'])
                ->where('lastname', $data['child_lastname'])
                ->first();

            $meal = Meal::where('type', $data['meal_type'])
                ->first();

            $data['meal_time'] = date('Y-m-d H:i:s', strtotime($data['meal_time']));


            $data['meal_id'] = $meal->id_meal;
            $data['child_id'] = $child->id_child;

            unset($data['child_firstname']);
            unset($data['child_lastname']);
            unset($data['meal_type']);
        }

        unset($data);


        // Insert the data in the meals table

        DB::table('child_meal')->insert($childMeals);
    }
}

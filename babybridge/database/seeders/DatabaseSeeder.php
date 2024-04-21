<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Child;
use App\Models\DiaperChange;
use App\Models\Meal;
use App\Models\MedicalRecord;
use App\Models\Role;
use App\Models\Type;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Child::factory(10)->create();

        $this->call([
            SectionSeeder::class,
            ChildSeeder::class,
            AttendanceSeeder::class,
            DiaperChangeSeeder::class,
            MealSeeder::class,
            ActivitySeeder::class,
            ChildActivitySeeder::class,
            MedicalRecordSeeder::class,
            NapSeeder::class,
            PhotoSeeder::class,
            ChildMealSeeder::class,
            ChildSectionSeeder::class,
            TypeSeeder::class,
            SectionTypeSeeder::class,



        ]);
    }
}

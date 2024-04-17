<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Child>
 */
class ChildFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'lastname' => $this->faker->lastName,
            'firstname' => $this->faker->firstName,
            'gender' => $this->faker->randomElement(['M', 'F']),
        ];
    }
}

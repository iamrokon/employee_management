<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'description' => $this->faker->catchPhrase(),
        ];
    }
}

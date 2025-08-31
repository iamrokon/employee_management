<?php
namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::inRandomOrder()->value('id'),
            'designation' => $this->faker->jobTitle(),
            'salary' => $this->faker->numberBetween(25000, 250000),
            'address' => $this->faker->address(),
            'joined_date' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
        ];
    }
}

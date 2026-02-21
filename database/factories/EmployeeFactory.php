<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = [
            'Staff', 'Senior Staff', 'Supervisor', 'Assistant Manager',
            'Manager', 'Senior Manager', 'General Manager', 'Director',
            'Vice President', 'Analyst', 'Engineer', 'Specialist',
            'Coordinator', 'Officer', 'Team Lead',
        ];

        $statuses = ['Permanent', 'Contract', 'Probation', 'Internship'];

        return [
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'nip' => 'EMP-' . str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'full_name' => $this->faker->name(),
            'position' => $this->faker->randomElement($positions),
            'employment_status' => $this->faker->randomElement($statuses),
            'join_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeContact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeContact>
 */
class EmployeeContactFactory extends Factory
{
    protected $model = EmployeeContact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $relations = ['Suami', 'Istri', 'Orang Tua', 'Saudara', 'Anak'];

        return [
            'employee_id' => Employee::factory(),
            'email_work' => $this->faker->unique()->companyEmail(),
            'email_personal' => $this->faker->optional(0.8)->safeEmail(),
            'phone_number' => $this->faker->optional(0.9)->numerify('08##########'),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->numerify('08##########'),
            'emergency_contact_relation' => $this->faker->randomElement($relations),
        ];
    }
}

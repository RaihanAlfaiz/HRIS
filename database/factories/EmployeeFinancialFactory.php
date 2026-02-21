<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeFinancial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeFinancial>
 */
class EmployeeFinancialFactory extends Factory
{
    protected $model = EmployeeFinancial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $banks = ['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'Bank Danamon', 'BTN', 'Permata Bank'];

        return [
            'employee_id' => Employee::factory(),
            'npwp' => $this->faker->optional(0.7)->numerify('##.###.###.#-###.###'),
            'bpjs_kesehatan' => $this->faker->optional(0.8)->numerify('#############'),
            'bpjs_ketenagakerjaan' => $this->faker->optional(0.8)->numerify('#############'),
            'bank_name' => $this->faker->optional(0.9)->randomElement($banks),
            'bank_account_number' => $this->faker->optional(0.9)->numerify('##########'),
        ];
    }
}

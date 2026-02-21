<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeProfile>
 */
class EmployeeProfileFactory extends Factory
{
    protected $model = EmployeeProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang',
            'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi',
            'Malang', 'Yogyakarta', 'Bogor', 'Solo', 'Denpasar',
        ];

        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        return [
            'employee_id' => Employee::factory(),
            'nik_ktp' => $this->faker->unique()->numerify('################'),
            'place_of_birth' => $this->faker->randomElement($cities),
            'date_of_birth' => $this->faker->dateTimeBetween('-55 years', '-20 years'),
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'religion' => $this->faker->randomElement($religions),
            'marital_status' => $this->faker->randomElement(['Belum Menikah', 'Menikah', 'Cerai']),
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O', null]),
            'address_ktp' => $this->faker->address(),
            'address_domicile' => $this->faker->optional(0.7)->address(),
        ];
    }
}

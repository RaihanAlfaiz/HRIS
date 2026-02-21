<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeContact;
use App\Models\EmployeeFinancial;
use App\Models\EmployeeProfile;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed 50 employees with complete related data.
     */
    public function run(): void
    {
        Employee::factory()
            ->count(50)
            ->create()
            ->each(function (Employee $employee) {
                EmployeeProfile::factory()->create(['employee_id' => $employee->id]);
                EmployeeContact::factory()->create(['employee_id' => $employee->id]);
                EmployeeFinancial::factory()->create(['employee_id' => $employee->id]);
            });
    }
}

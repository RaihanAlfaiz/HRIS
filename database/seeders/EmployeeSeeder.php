<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeContact;
use App\Models\EmployeeContract;
use App\Models\EmployeeFinancial;
use App\Models\EmployeeKpi;
use App\Models\EmployeeProfile;
use App\Models\Site;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed 50 employees with complete related data.
     */
    public function run(): void
    {
        $siteIds = Site::pluck('id')->toArray();
        $ratings = ['Excellent', 'Good', 'Average', 'Below Average', 'Poor'];

        Employee::factory()
            ->count(50)
            ->create()
            ->each(function (Employee $employee) use ($siteIds, $ratings) {
                // Assign random site
                if (!empty($siteIds)) {
                    $employee->update(['site_id' => fake()->randomElement($siteIds)]);
                }

                // Profile, Contact, Financial
                EmployeeProfile::factory()->create(['employee_id' => $employee->id]);
                EmployeeContact::factory()->create(['employee_id' => $employee->id]);
                EmployeeFinancial::factory()->create(['employee_id' => $employee->id]);

                // Contracts (1-3 per employee for Contract/Probation status)
                if (in_array($employee->employment_status, ['Contract', 'Probation'])) {
                    $contractCount = fake()->numberBetween(1, 3);
                    $startDate = $employee->join_date->copy();

                    for ($i = 0; $i < $contractCount; $i++) {
                        $endDate = $startDate->copy()->addMonths(fake()->randomElement([6, 12, 24]));

                        EmployeeContract::create([
                            'employee_id'     => $employee->id,
                            'contract_number' => 'SPK/' . str_pad($employee->id, 3, '0', STR_PAD_LEFT) . '/' . ($i + 1) . '/' . $startDate->year,
                            'contract_type'   => $i === 0 ? 'PKWT' : fake()->randomElement(['PKWT', 'Addendum']),
                            'start_date'      => $startDate,
                            'end_date'        => $endDate,
                            'notes'           => fake()->optional(0.3)->sentence(),
                        ]);

                        $startDate = $endDate->copy()->addDay();
                    }
                }

                // KPI (1-3 per employee)
                $kpiCount = fake()->numberBetween(1, 3);
                $periods = ['2024-Q1', '2024-Q2', '2024-H1', '2024-Q3', '2024-Q4', '2024-H2', '2025-Q1'];

                for ($i = 0; $i < $kpiCount; $i++) {
                    $score = fake()->randomFloat(2, 40, 100);
                    $rating = match (true) {
                        $score >= 90 => 'Excellent',
                        $score >= 75 => 'Good',
                        $score >= 60 => 'Average',
                        $score >= 45 => 'Below Average',
                        default      => 'Poor',
                    };

                    EmployeeKpi::create([
                        'employee_id' => $employee->id,
                        'period'      => $periods[$i] ?? '2025-Q' . ($i + 1),
                        'score'       => $score,
                        'rating'      => $rating,
                        'notes'       => fake()->optional(0.4)->sentence(),
                        'reviewed_by' => fake()->name(),
                    ]);
                }
            });
    }
}

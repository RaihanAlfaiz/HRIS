<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Human Resources',
            'Finance & Accounting',
            'Information Technology',
            'Marketing & Communications',
            'Sales & Business Development',
            'Operations',
            'Legal & Compliance',
            'Research & Development',
            'Customer Service',
            'General Affairs',
            'Procurement',
            'Quality Assurance',
            'Production',
            'Logistics & Supply Chain',
            'Corporate Planning',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}

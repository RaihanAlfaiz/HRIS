# HRIS Feature Implementation Plan

## Phase 1: Database Migrations

1. Add `role` column to `users` table (admin, hr, viewer)
2. Create `sites` table (id, name, code, address, timestamps)
3. Add `site_id` to `employees` table
4. Create `employee_contracts` table (employee_id, contract_number, start_date, end_date, type, notes)
5. Create `employee_kpis` table (employee_id, period, score, rating, notes, reviewed_by, timestamps)

## Phase 2: Models & Relationships

- Site model + Employee belongsTo Site
- EmployeeContract model
- EmployeeKpi model
- Update Employee (tenure accessor, site relation, contracts, kpis)
- Update User (role, helpers isAdmin/isHr/isViewer)

## Phase 3: Middleware & Authorization

- RoleMiddleware for route-level access control

## Phase 4: Controllers

- SiteController (CRUD + dashboard per site)
- EmployeeContractController (CRUD under employee)
- EmployeeKpiController (CRUD under employee)
- Update EmployeeController/form with site_id
- Update DashboardController with contract alerts

## Phase 5: Views

- Sites CRUD views
- Contract section in employee show
- KPI section in employee show
- Tenure display in employee show/index
- Contract expiry alerts in dashboard
- Role-based sidebar/navigation visibility

## Phase 6: Seeders

- SiteSeeder
- Update UserSeeder (3 roles)
- Update EmployeeSeeder (add site, contracts, KPIs)

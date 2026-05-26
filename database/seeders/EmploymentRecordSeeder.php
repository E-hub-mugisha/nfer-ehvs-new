<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmploymentRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch seeded employees and employers in order
        $employees = DB::table('employees')->orderBy('id')->pluck('id')->toArray();
        $employers = DB::table('employers')->orderBy('id')->pluck('id')->toArray();

        if (count($employees) < 10) {
            $this->command->warn('⚠  Less than 10 employees found. Run EmployeeSeeder first.');
            return;
        }

        if (count($employers) < 4) {
            $this->command->warn('⚠  Less than 4 employers found. Run EmployerSeeder first.');
            return;
        }

        // Each employee gets 2 records:
        //   Record 1 → terminated/resigned/contract-ended  (past job)
        //   Record 2 → active                              (current job)
        //
        // Employers are distributed round-robin across the 10 employees (4 employers × cycle).
        // Past employer ≠ current employer for the same employee.

        $records = [
            // ── Employee 1 : Mutoni Uwase ──────────────────────────────────────────
            [
                'employee_index'    => 0,
                'employer_index'    => 0,   // Remera Sector
                'job_title'         => 'Administrative Officer',
                'department'        => 'Public Administration',
                'start_date'        => '2014-03-01',
                'end_date'          => '2018-06-30',
                'employment_status' => 'resigned',
                'exit_reason'       => 'resignation',
                'remarks'           => 'Resigned to pursue further studies.',
            ],
            [
                'employee_index'    => 0,
                'employer_index'    => 2,   // Grand Legacy Hotel
                'job_title'         => 'Front Office Manager',
                'department'        => 'Hospitality & Guest Services',
                'start_date'        => '2019-01-15',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 2 : Habimana Nkurunziza ───────────────────────────────────
            [
                'employee_index'    => 1,
                'employer_index'    => 1,   // CCI Rwanda
                'job_title'         => 'Business Development Associate',
                'department'        => 'Trade & Investment',
                'start_date'        => '2010-05-01',
                'end_date'          => '2016-04-30',
                'employment_status' => 'contract-ended',
                'exit_reason'       => 'contract-expiry',
                'remarks'           => 'Fixed-term contract not renewed.',
            ],
            [
                'employee_index'    => 1,
                'employer_index'    => 3,   // Pride Connect Travel
                'job_title'         => 'Senior Tour Consultant',
                'department'        => 'Sales & Reservations',
                'start_date'        => '2017-02-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 3 : Ishimwe Kagabo ────────────────────────────────────────
            [
                'employee_index'    => 2,
                'employer_index'    => 2,   // Grand Legacy Hotel
                'job_title'         => 'Food & Beverage Supervisor',
                'department'        => 'Food & Beverage',
                'start_date'        => '2016-08-01',
                'end_date'          => '2020-07-31',
                'employment_status' => 'terminated',
                'exit_reason'       => 'dismissal',
                'remarks'           => 'Dismissed following departmental restructuring.',
            ],
            [
                'employee_index'    => 2,
                'employer_index'    => 0,   // Remera Sector
                'job_title'         => 'Community Development Officer',
                'department'        => 'Social Affairs',
                'start_date'        => '2021-01-04',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 4 : Uwimana Mukamana ──────────────────────────────────────
            [
                'employee_index'    => 3,
                'employer_index'    => 3,   // Pride Connect Travel
                'job_title'         => 'Travel Operations Coordinator',
                'department'        => 'Operations',
                'start_date'        => '2018-03-15',
                'end_date'          => '2021-03-14',
                'employment_status' => 'contract-ended',
                'exit_reason'       => 'contract-expiry',
                'remarks'           => 'Three-year contract completed.',
            ],
            [
                'employee_index'    => 3,
                'employer_index'    => 1,   // CCI Rwanda
                'job_title'         => 'Membership & Relations Officer',
                'department'        => 'Member Services',
                'start_date'        => '2021-09-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 5 : Ntwari Bizimana ───────────────────────────────────────
            [
                'employee_index'    => 4,
                'employer_index'    => 0,   // Remera Sector
                'job_title'         => 'Revenue Collection Officer',
                'department'        => 'Finance & Revenue',
                'start_date'        => '2012-06-01',
                'end_date'          => '2017-05-31',
                'employment_status' => 'resigned',
                'exit_reason'       => 'mutual-agreement',
                'remarks'           => 'Left by mutual agreement for a private sector opportunity.',
            ],
            [
                'employee_index'    => 4,
                'employer_index'    => 2,   // Grand Legacy Hotel
                'job_title'         => 'Accounting Officer',
                'department'        => 'Finance & Accounts',
                'start_date'        => '2017-10-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 6 : Akimana Ingabire ──────────────────────────────────────
            [
                'employee_index'    => 5,
                'employer_index'    => 1,   // CCI Rwanda
                'job_title'         => 'Events & Communications Officer',
                'department'        => 'Marketing & Communications',
                'start_date'        => '2015-02-01',
                'end_date'          => '2019-01-31',
                'employment_status' => 'resigned',
                'exit_reason'       => 'resignation',
                'remarks'           => 'Resigned to relocate closer to family.',
            ],
            [
                'employee_index'    => 5,
                'employer_index'    => 3,   // Pride Connect Travel
                'job_title'         => 'Marketing & Digital Officer',
                'department'        => 'Marketing',
                'start_date'        => '2019-06-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 7 : Mugisha Nshimiyimana ──────────────────────────────────
            [
                'employee_index'    => 6,
                'employer_index'    => 2,   // Grand Legacy Hotel
                'job_title'         => 'Security Supervisor',
                'department'        => 'Security & Safety',
                'start_date'        => '2017-04-01',
                'end_date'          => '2021-09-30',
                'employment_status' => 'terminated',
                'exit_reason'       => 'redundancy',
                'remarks'           => 'Position made redundant after security outsourcing.',
            ],
            [
                'employee_index'    => 6,
                'employer_index'    => 0,   // Remera Sector
                'job_title'         => 'Land & Infrastructure Officer',
                'department'        => 'Urban Planning',
                'start_date'        => '2022-01-10',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 8 : Umubyeyi Mukamurera ──────────────────────────────────
            [
                'employee_index'    => 7,
                'employer_index'    => 3,   // Pride Connect Travel
                'job_title'         => 'Customer Relations Executive',
                'department'        => 'Customer Experience',
                'start_date'        => '2013-09-01',
                'end_date'          => '2018-08-31',
                'employment_status' => 'contract-ended',
                'exit_reason'       => 'contract-expiry',
                'remarks'           => 'Five-year contract concluded.',
            ],
            [
                'employee_index'    => 7,
                'employer_index'    => 1,   // CCI Rwanda
                'job_title'         => 'Policy & Advocacy Officer',
                'department'        => 'Research & Policy',
                'start_date'        => '2019-03-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 9 : Hirwa Rurangwa ────────────────────────────────────────
            [
                'employee_index'    => 8,
                'employer_index'    => 0,   // Remera Sector
                'job_title'         => 'Environmental Health Officer',
                'department'        => 'Health & Sanitation',
                'start_date'        => '2016-11-01',
                'end_date'          => '2020-10-31',
                'employment_status' => 'resigned',
                'exit_reason'       => 'resignation',
                'remarks'           => 'Resigned to join the private sector.',
            ],
            [
                'employee_index'    => 8,
                'employer_index'    => 2,   // Grand Legacy Hotel
                'job_title'         => 'Housekeeping Supervisor',
                'department'        => 'Rooms Division',
                'start_date'        => '2021-03-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],

            // ── Employee 10 : Umutoni Nyirahabimana ───────────────────────────────
            [
                'employee_index'    => 9,
                'employer_index'    => 1,   // CCI Rwanda
                'job_title'         => 'Research & Data Analyst',
                'department'        => 'Economic Research',
                'start_date'        => '2019-07-01',
                'end_date'          => '2022-06-30',
                'employment_status' => 'contract-ended',
                'exit_reason'       => 'contract-expiry',
                'remarks'           => 'Three-year research contract completed.',
            ],
            [
                'employee_index'    => 9,
                'employer_index'    => 3,   // Pride Connect Travel
                'job_title'         => 'Business Development Officer',
                'department'        => 'Sales & Partnerships',
                'start_date'        => '2022-10-01',
                'end_date'          => null,
                'employment_status' => 'active',
                'exit_reason'       => null,
                'remarks'           => null,
            ],
        ];

        $now    = Carbon::now();
        $count  = 0;

        foreach ($records as $record) {
            DB::table('employment_records')->insert([
                'employee_id'       => $employees[$record['employee_index']],
                'employer_id'       => $employers[$record['employer_index']],
                'job_title'         => $record['job_title'],
                'department'        => $record['department'],
                'start_date'        => $record['start_date'],
                'end_date'          => $record['end_date'],
                'employment_status' => $record['employment_status'],
                'exit_reason'       => $record['exit_reason'],
                'remarks'           => $record['remarks'],
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
            $count++;
        }

        $this->command->info('');
        $this->command->info('┌──────────────────────────────────────────────────────────────────────┐');
        $this->command->info('│             Employment Records Summary                               │');
        $this->command->info('├────────────────────────────────────────┬────────────┬───────────────┤');
        $this->command->info('│ Employee                               │ Status     │ Employer      │');
        $this->command->info('├────────────────────────────────────────┼────────────┼───────────────┤');

        $inserted = DB::table('employment_records as er')
            ->join('employees as emp', 'er.employee_id', '=', 'emp.id')
            ->join('employers as empr', 'er.employer_id', '=', 'empr.id')
            ->select('emp.first_name', 'emp.last_name', 'er.employment_status', 'empr.company_name')
            ->orderBy('emp.id')
            ->orderBy('er.start_date')
            ->get();

        foreach ($inserted as $row) {
            $name    = str_pad($row->first_name . ' ' . $row->last_name, 38);
            $status  = str_pad($row->employment_status, 10);
            $company = str_pad(substr($row->company_name, 0, 13), 13);
            $this->command->info("│ {$name} │ {$status} │ {$company} │");
        }

        $this->command->info('└────────────────────────────────────────┴────────────┴───────────────┘');
        $this->command->info('');
        $this->command->info("✔  {$count} employment records seeded successfully (2 per employee).");
        $this->command->info('');
    }
}
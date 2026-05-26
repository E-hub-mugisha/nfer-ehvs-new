<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DisputeAndTransferSeeder extends Seeder
{
    public function run(): void
    {
        // ── Fetch seeded data ──────────────────────────────────────────────────
        $employees = DB::table('employees')->orderBy('id')->get(['id', 'first_name', 'last_name']);
        $employers = DB::table('employers')->orderBy('id')->get(['id', 'company_name']);
        $records   = DB::table('employment_records')->orderBy('id')->get();

        if ($employees->count() < 10 || $employers->count() < 4 || $records->count() < 20) {
            $this->command->warn('⚠  Prerequisite seeders missing. Run Employee → Employer → EmploymentRecord seeders first.');
            return;
        }

        $emp  = $employees->values();   // 0–9
        $empr = $employers->values();   // 0–3  [Remera, CCI, Grand Legacy, Pride Connect]
        $recs = $records->values();     // 0–19 (every 2 belong to the same employee)

        $now = Carbon::now();

        // Helper: get employment records for an employee (by index)
        // Record layout per employee: even index = past record, odd index = active record
        $pastRec    = fn(int $empIdx) => $recs[$empIdx * 2];       // terminated/resigned
        $activeRec  = fn(int $empIdx) => $recs[$empIdx * 2 + 1];   // active

        // ══════════════════════════════════════════════════════════════════════
        //  DISPUTES  (6 disputes across 6 different employees)
        // ══════════════════════════════════════════════════════════════════════
        $disputes = [
            // 1. Mutoni Uwase disputes her old Remera Sector record (resigned)
            [
                'employee_id'         => $emp[0]->id,
                'employment_record_id'=> $pastRec(0)->id,
                'description'         => 'My employment record at Remera Sector shows the exit reason as "resignation" but I was actually asked to leave due to budget cuts. I am requesting a correction to reflect "redundancy" as the true exit reason.',
                'evidence'            => null,
                'status'              => 'under-review',
            ],
            // 2. Ishimwe Kagabo disputes his dismissal at Grand Legacy Hotel
            [
                'employee_id'         => $emp[2]->id,
                'employment_record_id'=> $pastRec(2)->id,
                'description'         => 'The record incorrectly states that I was dismissed during departmental restructuring. I resigned voluntarily after accepting another offer. I request that the employment status and exit reason be corrected accordingly.',
                'evidence'            => null,
                'status'              => 'pending',
            ],
            // 3. Ntwari Bizimana disputes his start date at Grand Legacy Hotel
            [
                'employee_id'         => $emp[4]->id,
                'employment_record_id'=> $activeRec(4)->id,
                'description'         => 'My current employment record at Grand Legacy Hotel shows a start date of 2017-10-01, but my actual reporting date was 2017-08-15 as evidenced by my appointment letter. This discrepancy affects my leave and seniority calculations.',
                'evidence'            => null,
                'status'              => 'resolved',
            ],
            // 4. Akimana Ingabire disputes her job title at CCI Rwanda (past record)
            [
                'employee_id'         => $emp[5]->id,
                'employment_record_id'=> $pastRec(5)->id,
                'description'         => 'The recorded job title "Events & Communications Officer" does not reflect my actual role. I was promoted to "Senior Communications Manager" in 2017 but the record was never updated before my exit.',
                'evidence'            => null,
                'status'              => 'pending',
            ],
            // 5. Umubyeyi Mukamurera disputes contract-ended status at Pride Connect
            [
                'employee_id'         => $emp[7]->id,
                'employment_record_id'=> $pastRec(7)->id,
                'description'         => 'My record at Pride Connect Travel and Tours shows "contract-expiry" as the exit reason. In reality, my contract was terminated early without the notice period stipulated in my contract, and I was not paid my outstanding dues.',
                'evidence'            => null,
                'status'              => 'under-review',
            ],
            // 6. Umutoni Nyirahabimana disputes department info at CCI Rwanda
            [
                'employee_id'         => $emp[9]->id,
                'employment_record_id'=> $pastRec(9)->id,
                'description'         => 'My past employment record at CCI Rwanda lists my department as "Economic Research", but I worked primarily under the "Policy & Trade Facilitation" department. This misclassification is affecting my professional references.',
                'evidence'            => null,
                'status'              => 'rejected',
            ],
        ];

        DB::table('disputes')->insert(array_map(fn($d) => array_merge($d, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), $disputes));

        $this->command->info('');
        $this->command->info('✔  6 dispute records seeded.');

        // ══════════════════════════════════════════════════════════════════════
        //  TRANSFER REQUESTS  (5 requests — different employees, valid pairs)
        // ══════════════════════════════════════════════════════════════════════
        //
        // Rules enforced:
        //  - requesting_employer ≠ current_employer
        //  - current_employment_record must be the employee's ACTIVE record
        //  - proposed_start_date is after the active record's start_date

        $transfers = [
            // 1. Habimana Nkurunziza → transfer from Pride Connect to CCI Rwanda
            [
                'employee_id'                  => $emp[1]->id,
                'requesting_employer_id'       => $empr[1]->id,   // CCI Rwanda wants him
                'current_employer_id'          => $empr[3]->id,   // currently at Pride Connect
                'current_employment_record_id' => $activeRec(1)->id,
                'proposed_job_title'           => 'Senior Trade Development Officer',
                'proposed_department'          => 'Trade & Investment',
                'proposed_start_date'          => '2025-09-01',
                'reason'                       => 'CCI Rwanda seeks to onboard an experienced tourism and trade professional to strengthen its regional trade facilitation programs.',
                'status'                       => 'pending',
                'rejection_reason'             => null,
                'responded_at'                 => null,
            ],
            // 2. Uwimana Mukamana → transfer from CCI Rwanda to Grand Legacy Hotel
            [
                'employee_id'                  => $emp[3]->id,
                'requesting_employer_id'       => $empr[2]->id,   // Grand Legacy Hotel
                'current_employer_id'          => $empr[1]->id,   // currently at CCI Rwanda
                'current_employment_record_id' => $activeRec(3)->id,
                'proposed_job_title'           => 'Guest Relations & Events Manager',
                'proposed_department'          => 'Hospitality & Guest Services',
                'proposed_start_date'          => '2025-08-15',
                'reason'                       => 'Grand Legacy Hotel is expanding its MICE department and requires a skilled relationship management professional with strong institutional network.',
                'status'                       => 'approved',
                'rejection_reason'             => null,
                'responded_at'                 => Carbon::parse('2025-06-10 10:30:00'),
            ],
            // 3. Mugisha Nshimiyimana → transfer from Remera Sector to Pride Connect
            [
                'employee_id'                  => $emp[6]->id,
                'requesting_employer_id'       => $empr[3]->id,   // Pride Connect
                'current_employer_id'          => $empr[0]->id,   // currently at Remera Sector
                'current_employment_record_id' => $activeRec(6)->id,
                'proposed_job_title'           => 'Security & Logistics Coordinator',
                'proposed_department'          => 'Operations',
                'proposed_start_date'          => '2025-10-01',
                'reason'                       => 'Pride Connect Travel requires an experienced operations professional to oversee ground security and client logistics for safari and group tour packages.',
                'status'                       => 'rejected',
                'rejection_reason'             => 'The current employer, Remera Sector, has declined the transfer citing an ongoing land registration project that requires the employee\'s continued involvement through Q4 2025.',
                'responded_at'                 => Carbon::parse('2025-06-18 14:00:00'),
            ],
            // 4. Hirwa Rurangwa → transfer from Grand Legacy Hotel to Remera Sector
            [
                'employee_id'                  => $emp[8]->id,
                'requesting_employer_id'       => $empr[0]->id,   // Remera Sector
                'current_employer_id'          => $empr[2]->id,   // currently at Grand Legacy Hotel
                'current_employment_record_id' => $activeRec(8)->id,
                'proposed_job_title'           => 'Environmental & Sanitation Inspector',
                'proposed_department'          => 'Health & Sanitation',
                'proposed_start_date'          => '2025-11-01',
                'reason'                       => 'Remera Sector is recruiting an experienced environmental health professional to lead its community sanitation improvement initiative.',
                'status'                       => 'pending',
                'rejection_reason'             => null,
                'responded_at'                 => null,
            ],
            // 5. Umutoni Nyirahabimana → transfer from Pride Connect to CCI Rwanda
            [
                'employee_id'                  => $emp[9]->id,
                'requesting_employer_id'       => $empr[1]->id,   // CCI Rwanda
                'current_employer_id'          => $empr[3]->id,   // currently at Pride Connect
                'current_employment_record_id' => $activeRec(9)->id,
                'proposed_job_title'           => 'Research & Policy Analyst',
                'proposed_department'          => 'Economic Research',
                'proposed_start_date'          => '2025-09-15',
                'reason'                       => 'CCI Rwanda is reinstating its economic research unit and wishes to recruit a data-driven analyst with prior experience in trade facilitation and market research.',
                'status'                       => 'pending',
                'rejection_reason'             => null,
                'responded_at'                 => null,
            ],
        ];

        DB::table('transfer_requests')->insert(array_map(fn($t) => array_merge($t, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), $transfers));

        $this->command->info('✔  5 transfer request records seeded.');

        // ── Summary tables ─────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('┌──────────────────────────────────────────────────────────────────────────┐');
        $this->command->info('│                        DISPUTES SUMMARY                                  │');
        $this->command->info('├─────────────────────────────────┬──────────────┬────────────────────────┤');
        $this->command->info('│ Employee                        │ Status       │ Record Type            │');
        $this->command->info('├─────────────────────────────────┼──────────────┼────────────────────────┤');

        $disputeSummary = [
            [$emp[0], 'under-review', 'Past (Remera Sector)'],
            [$emp[2], 'pending',      'Past (Grand Legacy)'],
            [$emp[4], 'resolved',     'Active (Grand Legacy)'],
            [$emp[5], 'pending',      'Past (CCI Rwanda)'],
            [$emp[7], 'under-review', 'Past (Pride Connect)'],
            [$emp[9], 'rejected',     'Past (CCI Rwanda)'],
        ];

        foreach ($disputeSummary as [$e, $status, $type]) {
            $name   = str_pad($e->first_name . ' ' . $e->last_name, 31);
            $status = str_pad($status, 12);
            $type   = str_pad($type, 22);
            $this->command->info("│ {$name} │ {$status} │ {$type} │");
        }
        $this->command->info('└─────────────────────────────────┴──────────────┴────────────────────────┘');

        $this->command->info('');
        $this->command->info('┌───────────────────────────────────────────────────────────────────────────┐');
        $this->command->info('│                     TRANSFER REQUESTS SUMMARY                             │');
        $this->command->info('├──────────────────────────┬──────────────────────────┬────────────────────┤');
        $this->command->info('│ Employee                 │ Requesting Employer      │ Status             │');
        $this->command->info('├──────────────────────────┼──────────────────────────┼────────────────────┤');

        $transferSummary = [
            [$emp[1], $empr[1], 'pending'],
            [$emp[3], $empr[2], 'approved'],
            [$emp[6], $empr[3], 'rejected'],
            [$emp[8], $empr[0], 'pending'],
            [$emp[9], $empr[1], 'pending'],
        ];

        foreach ($transferSummary as [$e, $er, $status]) {
            $name    = str_pad($e->first_name . ' ' . $e->last_name, 24);
            $company = str_pad(substr($er->company_name, 0, 24), 24);
            $status  = str_pad($status, 18);
            $this->command->info("│ {$name} │ {$company} │ {$status} │");
        }
        $this->command->info('└──────────────────────────┴──────────────────────────┴────────────────────┘');
        $this->command->info('');
    }
}
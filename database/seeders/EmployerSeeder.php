<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        $employers = [
            [
                'company_name' => 'Remera Sector',
                'rdb_number'   => 'RDB/RS/2008/0012',
                'tin_number'   => '100458632',
                'email'        => 'info@remerasector.gov.rw',
                'phone'        => '+250788301200',
                'address'      => 'Remera Sector Office, Gasabo District, Kigali',
                'status'       => 'active',
                // User credentials
                'user_name'    => 'Remera Sector',
                'user_email'   => 'admin@remerasector.gov.rw',
                'password'     => 'Remera@2025',
            ],
            [
                'company_name' => 'CCI Rwanda',
                'rdb_number'   => 'RDB/CCI/2011/0347',
                'tin_number'   => '100672841',
                'email'        => 'info@ccirwanda.org',
                'phone'        => '+250252580200',
                'address'      => 'KN 3 Ave, Nyarugenge, Kigali',
                'status'       => 'active',
                'user_name'    => 'CCI Rwanda',
                'user_email'   => 'admin@ccirwanda.org',
                'password'     => 'CciRwanda@2025',
            ],
            [
                'company_name' => 'Grand Legacy Hotel',
                'rdb_number'   => 'RDB/HTL/2015/1124',
                'tin_number'   => '100893456',
                'email'        => 'reservations@grandlegacyhotel.rw',
                'phone'        => '+250788450900',
                'address'      => 'KG 7 Ave, Kicukiro District, Kigali',
                'status'       => 'active',
                'user_name'    => 'Grand Legacy Hotel',
                'user_email'   => 'admin@grandlegacyhotel.rw',
                'password'     => 'GrandLegacy@2025',
            ],
            [
                'company_name' => 'Pride Connect Travel and Tours Ltd',
                'rdb_number'   => 'RDB/TTL/2017/2089',
                'tin_number'   => '101023784',
                'email'        => 'info@prideconnecttours.rw',
                'phone'        => '+250733621500',
                'address'      => 'KK 15 Rd, Remera, Gasabo District, Kigali',
                'status'       => 'active',
                'user_name'    => 'Pride Connect Travel and Tours Ltd',
                'user_email'   => 'admin@prideconnecttours.rw',
                'password'     => 'PrideConnect@2025',
            ],
        ];

        $this->command->info('');
        $this->command->info('┌──────────────────────────────────────────────────────────────────────┐');
        $this->command->info('│                  Employer & User Credentials                         │');
        $this->command->info('├─────────────────────────────────┬────────────────────────────────────┤');
        $this->command->info('│ Email                           │ Password                           │');
        $this->command->info('├─────────────────────────────────┼────────────────────────────────────┤');

        foreach ($employers as $employer) {
            // Create linked user account
            $userId = DB::table('users')->insertGetId([
                'name'              => $employer['user_name'],
                'email'             => $employer['user_email'],
                'password'          => Hash::make($employer['password']),
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);

            // Insert employer linked to user
            DB::table('employers')->insert([
                'company_name' => $employer['company_name'],
                'rdb_number'   => $employer['rdb_number'],
                'tin_number'   => $employer['tin_number'],
                'email'        => $employer['email'],
                'phone'        => $employer['phone'],
                'address'      => $employer['address'],
                'status'       => $employer['status'],
                'user_id'      => $userId,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);

            $email    = str_pad($employer['user_email'], 31);
            $password = str_pad($employer['password'], 34);
            $this->command->info("│ {$email} │ {$password} │");
        }

        $this->command->info('└─────────────────────────────────┴────────────────────────────────────┘');
        $this->command->info('');
        $this->command->info('✔  4 employers and linked user accounts seeded successfully.');
        $this->command->info('');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'nid'        => '1199880012345678',
                'first_name' => 'Mutoni',
                'last_name'  => 'Uwase',
                'gender'     => 'Female',
                'dob'        => '1988-03-14',
                'phone'      => '+250788123456',
                'email'      => 'mutoni.uwase@gmail.com',
                'photo'      => null,
                'district'   => 'Kigali',
                'sector'     => 'Nyarugenge',
            ],
            [
                'nid'        => '1199560023456789',
                'first_name' => 'Habimana',
                'last_name'  => 'Nkurunziza',
                'gender'     => 'Male',
                'dob'        => '1956-07-22',
                'phone'      => '+250722234567',
                'email'      => 'habimana.nkurunziza@yahoo.com',
                'photo'      => null,
                'district'   => 'Musanze',
                'sector'     => 'Muhoza',
            ],
            [
                'nid'        => '1199270034567890',
                'first_name' => 'Ishimwe',
                'last_name'  => 'Kagabo',
                'gender'     => 'Male',
                'dob'        => '1992-11-05',
                'phone'      => '+250733345678',
                'email'      => 'ishimwe.kagabo@outlook.com',
                'photo'      => null,
                'district'   => 'Huye',
                'sector'     => 'Ngoma',
            ],
            [
                'nid'        => '1199450045678901',
                'first_name' => 'Uwimana',
                'last_name'  => 'Mukamana',
                'gender'     => 'Female',
                'dob'        => '1995-01-30',
                'phone'      => '+250788456789',
                'email'      => 'uwimana.mukamana@gmail.com',
                'photo'      => null,
                'district'   => 'Gasabo',
                'sector'     => 'Kimironko',
            ],
            [
                'nid'        => '1198590056789012',
                'first_name' => 'Ntwari',
                'last_name'  => 'Bizimana',
                'gender'     => 'Male',
                'dob'        => '1985-09-18',
                'phone'      => '+250722567890',
                'email'      => 'ntwari.bizimana@gmail.com',
                'photo'      => null,
                'district'   => 'Rubavu',
                'sector'     => 'Gisenyi',
            ],
            [
                'nid'        => '1199160067890123',
                'first_name' => 'Akimana',
                'last_name'  => 'Ingabire',
                'gender'     => 'Female',
                'dob'        => '1991-06-12',
                'phone'      => '+250733678901',
                'email'      => 'akimana.ingabire@outlook.com',
                'photo'      => null,
                'district'   => 'Nyamagabe',
                'sector'     => 'Gasaka',
            ],
            [
                'nid'        => '1199370078901234',
                'first_name' => 'Mugisha',
                'last_name'  => 'Nshimiyimana',
                'gender'     => 'Male',
                'dob'        => '1993-04-25',
                'phone'      => '+250788789012',
                'email'      => 'mugisha.nshimiyimana@gmail.com',
                'photo'      => null,
                'district'   => 'Rwamagana',
                'sector'     => 'Kigabiro',
            ],
            [
                'nid'        => '1198480089012345',
                'first_name' => 'Umubyeyi',
                'last_name'  => 'Mukamurera',
                'gender'     => 'Female',
                'dob'        => '1984-12-08',
                'phone'      => '+250722890123',
                'email'      => 'umubyeyi.mukamurera@yahoo.com',
                'photo'      => null,
                'district'   => 'Nyanza',
                'sector'     => 'Busasamana',
            ],
            [
                'nid'        => '1199090090123456',
                'first_name' => 'Hirwa',
                'last_name'  => 'Rurangwa',
                'gender'     => 'Male',
                'dob'        => '1990-08-17',
                'phone'      => '+250733901234',
                'email'      => 'hirwa.rurangwa@gmail.com',
                'photo'      => null,
                'district'   => 'Burera',
                'sector'     => 'Cyanika',
            ],
            [
                'nid'        => '1199780001234567',
                'first_name' => 'Umutoni',
                'last_name'  => 'Nyirahabimana',
                'gender'     => 'Female',
                'dob'        => '1997-02-03',
                'phone'      => '+250788012345',
                'email'      => 'umutoni.nyirahabimana@gmail.com',
                'photo'      => null,
                'district'   => 'Kicukiro',
                'sector'     => 'Niboye',
            ],
        ];

        $this->command->info('');
        $this->command->info('┌─────────────────────────────────────────────────────────────┐');
        $this->command->info('│              Employee & User Credentials                    │');
        $this->command->info('├──────────────────────────────┬──────────────────────────────┤');
        $this->command->info('│ Email                        │ Password                     │');
        $this->command->info('├──────────────────────────────┼──────────────────────────────┤');

        foreach ($employees as $employee) {
            // Derive a default password: first_name + @2025 (e.g. Mutoni@2025)
            $plainPassword = $employee['first_name'] . '@2025';

            // Create the user account
            $userId = DB::table('users')->insertGetId([
                'name'              => $employee['first_name'] . ' ' . $employee['last_name'],
                'email'             => $employee['email'],
                'password'          => Hash::make($plainPassword),
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);

            // Insert the employee linked to the user
            DB::table('employees')->insert([
                'nid'        => $employee['nid'],
                'first_name' => $employee['first_name'],
                'last_name'  => $employee['last_name'],
                'gender'     => $employee['gender'],
                'dob'        => $employee['dob'],
                'phone'      => $employee['phone'],
                'email'      => $employee['email'],
                'photo'      => $employee['photo'],
                'district'   => $employee['district'],
                'sector'     => $employee['sector'],
                'user_id'    => $userId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Print credentials in the table
            $email    = str_pad($employee['email'], 28);
            $password = str_pad($plainPassword, 28);
            $this->command->info("│ {$email} │ {$password} │");
        }

        $this->command->info('└──────────────────────────────┴──────────────────────────────┘');
        $this->command->info('');
        $this->command->info('✔  10 employees and linked user accounts seeded successfully.');
        $this->command->info('');
    }
}
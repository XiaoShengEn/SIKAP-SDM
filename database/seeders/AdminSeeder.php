<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Default dev accounts (change if you want).
        // NIP must be 18 digits.
        $accounts = [
            [
                'nama_admin' => 'Super Admin',
                'nip' => '123456789012345678',
                'password' => 'admin12345',
                'bagian' => 'SDM',
                'role_admin' => 'superadmin',
            ],
            [
                'nama_admin' => 'Normal Admin',
                'nip' => '234567890123456789',
                'password' => 'admin12345',
                'bagian' => 'SDM',
                'role_admin' => 'normaladmin',
            ],
        ];

        foreach ($accounts as $a) {
            DB::table('tb_admin')->updateOrInsert(
                ['nip' => $a['nip']],
                [
                    'nama_admin' => $a['nama_admin'],
                    'password_admin' => Hash::make($a['password']),
                    'bagian' => $a['bagian'],
                    'role_admin' => $a['role_admin'],
                ]
            );
        }
    }
}


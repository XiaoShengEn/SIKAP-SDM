<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\KemendagriPegawaiService;

class FetchBirthdayPegawai extends Command
{
    protected $signature = 'kemendagri:fetch-birthday';

    protected $description = 'Ambil data ulang tahun pegawai dari API Kemendagri';

    public function handle()
    {
        try {
            $response = KemendagriPegawaiService::getBirthdayToday();

            // kalau tidak ada data, anggap sukses tapi kosong
            if (!isset($response['data']) || !is_array($response['data'])) {

                DB::table('birthday_today')->truncate();

                $this->info('Tidak ada pegawai berulang tahun hari ini.');
                return Command::SUCCESS;
            }

            DB::table('birthday_today')->truncate();

            foreach ($response['data'] as $row) {
                DB::table('birthday_today')->insert([
                    'tanggal'    => $row['tanggal_lahir'] ?? null,
                    'nama'       => $row['nama'] ?? null,
                    'created_at' => now(),
                ]);
            }

            $this->info('Berhasil update: ' . count($response['data']) . ' data');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Gagal fetch data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

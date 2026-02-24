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
            if (!env('KEMENDAGRI_API_USER') || !env('KEMENDAGRI_API_PASS')) {
                $this->error('Kredensial Kemendagri belum diisi (KEMENDAGRI_API_USER/PASS).');
                return Command::FAILURE;
            }

            $response = KemendagriPegawaiService::getBirthdayToday();

            if (!isset($response['data']) || !is_array($response['data'])) {
                $message = $response['message'] ?? 'Format response API tidak valid.';
                throw new \Exception($message);
            }

            $data = $response['data'];

            DB::table('birthday_today')->delete();

            foreach ($data as $row) {
                DB::table('birthday_today')->insert([
                    'tanggal'    => $row['tanggal_lahir'] ?? null,
                    'nama'       => $row['nama'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (count($data) === 0) {
                $this->info('Tidak ada pegawai berulang tahun hari ini.');
                return Command::SUCCESS;
            }

            $this->info('Berhasil update: ' . count($response['data']) . ' data');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Gagal fetch data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

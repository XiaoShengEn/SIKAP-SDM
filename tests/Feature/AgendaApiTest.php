<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.agenda_api.key', 'agenda-secret');
        config()->set('services.agenda_api.timezone', 'Asia/Jakarta');
    }

    public function test_agenda_api_requires_a_valid_key(): void
    {
        $response = $this->getJson('/api/agenda');

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Agenda API key tidak valid.',
            ]);
    }

    public function test_agenda_api_returns_filtered_agenda_with_sync_metadata(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 25, 8, 0, 0, 'Asia/Jakarta'));

        $pastAgenda = Kegiatan::create([
            'tanggal_kegiatan' => '2026-03-24',
            'jam' => '08:00',
            'nama_kegiatan' => 'Agenda Kemarin',
            'tempat' => 'Ruang A',
            'disposisi' => null,
            'keterangan' => null,
        ]);

        $todayAgenda = Kegiatan::create([
            'tanggal_kegiatan' => '2026-03-25',
            'jam' => '09:00',
            'nama_kegiatan' => 'Agenda Hari Ini',
            'tempat' => 'Ruang B',
            'disposisi' => 'Sekretariat',
            'keterangan' => 'Sinkronisasi',
        ]);

        $upcomingAgenda = Kegiatan::create([
            'tanggal_kegiatan' => '2026-03-27',
            'jam' => '10:00',
            'nama_kegiatan' => 'Agenda Mendatang',
            'tempat' => 'Ruang C',
            'disposisi' => null,
            'keterangan' => null,
        ]);

        $pastAgenda->forceFill([
            'updated_at' => Carbon::create(2026, 3, 24, 7, 0, 0, 'Asia/Jakarta'),
        ])->save();

        $todayAgenda->forceFill([
            'updated_at' => Carbon::create(2026, 3, 25, 9, 30, 0, 'Asia/Jakarta'),
        ])->save();

        $upcomingAgenda->forceFill([
            'updated_at' => Carbon::create(2026, 3, 25, 11, 0, 0, 'Asia/Jakarta'),
        ])->save();

        $response = $this
            ->withHeader('X-Agenda-Api-Key', 'agenda-secret')
            ->getJson('/api/agenda?updated_since=2026-03-25 09:00:00&sort=date_asc');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('meta.filters.sort', 'date_asc')
            ->assertJsonPath('meta.filters.updated_since', '2026-03-25T09:00:00+07:00')
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.nama_kegiatan', 'Agenda Hari Ini')
            ->assertJsonPath('data.0.status', 'today')
            ->assertJsonPath('data.1.nama_kegiatan', 'Agenda Mendatang')
            ->assertJsonPath('data.1.status', 'other');

        Carbon::setTestNow();
    }

    public function test_agenda_api_excludes_past_agenda_by_default(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 25, 8, 0, 0, 'Asia/Jakarta'));

        Kegiatan::create([
            'tanggal_kegiatan' => '2026-03-24',
            'jam' => '08:00',
            'nama_kegiatan' => 'Agenda Lama',
            'tempat' => 'Ruang A',
            'disposisi' => null,
            'keterangan' => null,
        ]);

        Kegiatan::create([
            'tanggal_kegiatan' => '2026-03-26',
            'jam' => '09:00',
            'nama_kegiatan' => 'Agenda Baru',
            'tempat' => 'Ruang B',
            'disposisi' => null,
            'keterangan' => null,
        ]);

        $response = $this
            ->withToken('agenda-secret')
            ->getJson('/api/agenda');

        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama_kegiatan', 'Agenda Baru');

        Carbon::setTestNow();
    }
}

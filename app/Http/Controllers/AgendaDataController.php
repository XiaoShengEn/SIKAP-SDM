<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgendaDataController extends Controller
{
    public function list(Request $request)
    {
        $perPage = 5;

        $kegiatan = Kegiatan::orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('jam', 'asc')
            ->paginate($perPage);

        $data = $kegiatan->getCollection()->map(
            fn (Kegiatan $agenda) => $this->transformAgenda($agenda)
        );

        return response()->json([
            'data' => $data,
            'current_page' => $kegiatan->currentPage(),
            'last_page' => $kegiatan->lastPage(),
            'per_page' => $kegiatan->perPage(),
            'total' => $kegiatan->total(),
        ]);
    }

    public function apiIndex(Request $request)
    {
        $validated = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['today', 'tomorrow', 'upcoming', 'past'])],
            'include_past' => ['nullable', 'boolean'],
            'updated_since' => ['nullable', 'date'],
            'sort' => ['nullable', Rule::in(['agenda', 'date_asc', 'date_desc'])],
        ]);

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 100));
        $timezone = (string) config('services.agenda_api.timezone', config('app.timezone', 'Asia/Jakarta'));
        $today = Carbon::today($timezone);
        $tomorrow = $today->copy()->addDay();

        $query = Kegiatan::query();

        if (!($validated['include_past'] ?? false) && empty($validated['status']) && empty($validated['from_date'])) {
            $query->whereDate('tanggal_kegiatan', '>=', $today->toDateString());
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('tanggal_kegiatan', '>=', Carbon::parse($validated['from_date'], $timezone)->toDateString());
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('tanggal_kegiatan', '<=', Carbon::parse($validated['to_date'], $timezone)->toDateString());
        }

        if (!empty($validated['updated_since'])) {
            $query->where('updated_at', '>=', Carbon::parse($validated['updated_since'], $timezone));
        }

        if (!empty($validated['status'])) {
            match ($validated['status']) {
                'today' => $query->whereDate('tanggal_kegiatan', $today->toDateString()),
                'tomorrow' => $query->whereDate('tanggal_kegiatan', $tomorrow->toDateString()),
                'upcoming' => $query->whereDate('tanggal_kegiatan', '>=', $today->toDateString()),
                'past' => $query->whereDate('tanggal_kegiatan', '<', $today->toDateString()),
            };
        }

        $sort = $validated['sort'] ?? 'agenda';

        if ($sort === 'date_asc') {
            $query->orderBy('tanggal_kegiatan')->orderBy('jam');
        } elseif ($sort === 'date_desc') {
            $query->orderByDesc('tanggal_kegiatan')->orderByDesc('jam');
        } else {
            $query->agendaOrder();
        }

        $agenda = $query->paginate($perPage)->appends($request->query());
        $data = $agenda->getCollection()->map(
            fn (Kegiatan $item) => $this->transformAgenda($item, $timezone)
        );

        return response()->json([
            'success' => true,
            'message' => 'Data agenda berhasil diambil.',
            'data' => $data,
            'meta' => [
                'current_page' => $agenda->currentPage(),
                'last_page' => $agenda->lastPage(),
                'per_page' => $agenda->perPage(),
                'total' => $agenda->total(),
                'timezone' => $timezone,
                'generated_at' => now($timezone)->toIso8601String(),
                'filters' => [
                    'from_date' => $validated['from_date'] ?? null,
                    'to_date' => $validated['to_date'] ?? null,
                    'status' => $validated['status'] ?? null,
                    'include_past' => (bool) ($validated['include_past'] ?? false),
                    'updated_since' => !empty($validated['updated_since'])
                        ? Carbon::parse($validated['updated_since'], $timezone)->toIso8601String()
                        : null,
                    'sort' => $sort,
                ],
            ],
        ]);
    }

    private function transformAgenda(Kegiatan $kegiatan, string $timezone): array
    {
        $tanggal = Carbon::parse($kegiatan->tanggal_kegiatan, $timezone)->startOfDay();
        $today = Carbon::today($timezone);
        $tanggalValue = $tanggal->toDateString();
        $todayValue = $today->toDateString();
        $tomorrowValue = $today->copy()->addDay()->toDateString();

        if ($tanggalValue === $todayValue) {
            $status = 'today';
        } elseif ($tanggalValue === $tomorrowValue) {
            $status = 'tomorrow';
        } elseif ($tanggalValue > $todayValue) {
            $status = 'other';
        } else {
            $status = 'past';
        }

        return [
            'id' => $kegiatan->kegiatan_id,
            'kegiatan_id' => $kegiatan->kegiatan_id,
            'tanggal_kegiatan' => $kegiatan->tanggal_kegiatan,
            'tanggal_label' => $tanggal->translatedFormat('l, d F Y'),
            'jam' => $kegiatan->jam,
            'nama_kegiatan' => $kegiatan->nama_kegiatan,
            'tempat' => $kegiatan->tempat,
            'disposisi' => $kegiatan->disposisi,
            'keterangan' => $kegiatan->keterangan,
            'status' => $status,
            'created_at' => $kegiatan->created_at?->toIso8601String(),
            'updated_at' => $kegiatan->updated_at?->toIso8601String(),
        ];
    }
}

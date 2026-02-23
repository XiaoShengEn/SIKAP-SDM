<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKAP SDM – Agenda Kegiatan Pimpinan Biro SDM</title>

    <!-- BOOTSTRAP + ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('welcome.css') }}">
</head>

<body>

    <div class="container-fluid">

        <div class="page-header mb-4 header-grid align-items-center gap-2">

            <!-- TANGGAL -->
            <div id="dateDisplay" class="fw-bold fs-3 text-white px-4 py-2 rounded shadow-sm">
                <i class="fas fa-calendar-day me-3 text-warning"></i>
                <span id="dateText">Rabu, 07 Januari 2026</span>
            </div>

            <!-- JUDUL -->
            <h1 class="text-center fw-bold mb-0">
                AGENDA PIMPINAN BIRO SDM
            </h1>

            <!-- JAM -->
            <div id="clockDisplay" class="fw-bold text-white px-4 py-2 rounded shadow-sm">
                <i class="fas fa-clock me-3 text-info"></i>
                <span id="timeText">19:54:18</span>
            </div>

        </div>

        <div class="row content-row">

            <!-- PROFIL -->
            <div class="col-12 col-md-6">
                <div class="card h-100 modern-card">
                    <div class="card-header bg-primary text-white modern-card-header">
                        <h5>
                            <i class="fas fa-user-tie me-2"></i> Profil Pimpinan
                        </h5>
                    </div>

                    <div class="card-body">
                        <div id="carouselPimpinan" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                                @forelse ($profil as $i => $p)
                                @php
                                $foto = $p->foto_pimpinan
                                ? asset('uploads/profil/' . $p->foto_pimpinan)
                                : asset('images/default_pimpinan.jpg');
                                @endphp

                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                    <div class="text-center">
                                        <img src="{{ $foto }}" class="img-profile mb-3">
                                        <h4 class="fw-bold">{{ $p->nama_pimpinan }}</h4>
                                        <p class="text-muted">{{ $p->jabatan_pimpinan }}</p>
                                    </div>
                                </div>

                                @empty
                                <div class="carousel-item active">
                                    <div class="text-center py-4">
                                        <img src="{{ asset('images/default_pimpinan.jpg') }}" class="img-profile mb-3">
                                        <h4 class="fw-bold">Belum Ada Profil</h4>
                                        <p class="text-muted">Silakan tambahkan di admin</p>
                                    </div>
                                </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIDEO -->
            <div class="col-12 col-md-6 content-col">
                <!-- FIX: hapus h-100 -->
                <div class="card modern-card content-card">
                    <div class="card-header bg-primary text-white modern-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-video me-2"></i> Video Kegiatan
                        </h5>
                    </div>

                    <!-- FIX: tv-card-body biar video bisa gede ngisi -->
                    <div class="card-body content-card-body">
                        <div class="video-wrapper">
                            <video id="vidA" class="video-player" autoplay playsinline muted></video>
                            <video id="vidB" class="video-player" autoplay playsinline muted></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AGENDA (NO CRUD) -->
        <div class="card modern-card mt-4 agenda-card">
            <div class="card-header bg-primary text-white modern-card-header">
                <h5>
                    <i class="fas fa-calendar-check me-2"></i> Agenda Biro SDM
                </h5>
            </div>

            <div class="card-body py-1">
                <!-- PAKAI WRAPPER ADMIN + TV MODE -->
                <div class="admin-table-wrapper agenda-scroll-container" id="agendaScroll">
                    <table class="table table-hover mb-0 py">
                        <thead class="sticky-thead-admin">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Kegiatan</th>
                                <th>Tempat</th>
                                <th>Disposisi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody id="agendaTbody">
                            @forelse ($agendaKegiatan as $item)
                            @php
                            $today = \Carbon\Carbon::today('Asia/Jakarta');

                            $date = \Carbon\Carbon::parse($item->tanggal_kegiatan, 'Asia/Jakarta')
                            ->startOfDay();

                            // parsing jam (kalau ada)
                            $jam = $item->jam
                            ? \Carbon\Carbon::parse($item->jam, 'Asia/Jakarta')
                            : null;

                            if ($date->equalTo($today)) {
                            $cls = 'agenda-today';
                            } elseif ($date->equalTo($today->copy()->addDay())) {
                            $cls = 'agenda-tomorrow';
                            } else {
                            $cls = 'agenda-other';
                            }
                            @endphp

                            <tr class="{{ $cls }}" {{ $cls === 'agenda-today' ? 'data-pinned=true' : '' }}>
                                <td>
                                    {{ $date->translatedFormat('l, d F Y') }}
                                    @if($jam)
                                    - {{ $jam->format('H.i') }} WIB
                                    @endif
                                </td>
                                <td>{{ $item->nama_kegiatan }}</td>
                                <td>{{ $item->tempat }}</td>
                                <td>{{ $item->disposisi }}</td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="5" class="text-start text-muted py-3">
                                    Belum ada agenda
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RUNNING TEXT -->
        <div class="running-text-container mt-2 tv-running-text">
            <div id="runningContainer">
                <span id="runningText">
                    {{ count($runningtext ?? []) ? implode(' | ', $runningtext) : '-' }}
                </span>
            </div>
        </div>

    </div>

    <!-- ✅ OVERLAY TRANSPARAN (tangkep 1x klik/tap/OK) -->
    <div id="unlockAudio"
        style="position:fixed; inset:0; z-index:9999; background:transparent; pointer-events:none;"></div>

    <!-- JS -->
    <script>
        window.TV_DATA = {
            playlist: @json($playlist ?? []),
            runningtext: @json(count($runningtext ?? []) ? $runningtext : ['-']),
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="/build/assets/echo.js"></script>
    <script src="{{ asset('welcome.js') }}"></script> --}}

    @vite(['resources/js/app.js', 'resources/js/welcome.js'])

    <script>
        // Fallback clock/date updater (keeps working even if Vite modules fail to load).
        (function() {
            const dateEl = document.getElementById('dateText');
            const timeEl = document.getElementById('timeText');

            const dateFmt = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            });

            function tick() {
                const now = new Date();
                if (dateEl) dateEl.textContent = dateFmt.format(now);
                if (timeEl) {
                    timeEl.textContent = now
                        .toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        })
                        .replace(/\./g, ':');
                }
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>

</body>

</html>
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

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg%20width%3D%2250%22%20height%3D%2252%22%20viewBox%3D%220%200%2050%2052%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Ctitle%3ELogomark%3C%2Ftitle%3E%3Cpath%20d%3D%22M49.626%2011.564a.809.809%200%200%201%20.028.209v10.972a.8.8%200%200%201-.402.694l-9.209%205.302V39.25c0%20.286-.152.55-.4.694L20.42%2051.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805%200%200%201-.41%200c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402%2039.944A.801.801%200%200%201%200%2039.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802%200%200%201%20.8%200l9.61%205.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809%200%200%201%20.028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801%200%200%201%20.8%200l9.61%205.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574%2010.718v-9.124l-3.363%201.936-4.646%202.675v9.124l8.01-4.611zm-9.61%2016.505v-9.13l-4.57%202.61-13.05%207.448v9.216l17.62-10.144zM1.602%207.719v31.068L19.22%2048.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965%209.654%201.602%207.72zm8.81-5.994L2.405%206.334l8.005%204.609%208.006-4.61-8.006-4.608zm4.164%2028.764l4.645-2.674V7.719l-3.363%201.936-4.646%202.675v20.096l3.364-1.937zM39.243%207.164l-8.006%204.609%208.006%204.609%208.005-4.61-8.005-4.608zm-.801%2010.605l-4.646-2.675-3.363-1.936v9.124l4.645%202.674%203.364%201.937v-9.124zM20.02%2038.33l11.743-6.704%205.87-3.35-8-4.606-9.211%205.303-8.395%204.833%207.993%204.524z%22%20fill%3D%22%23FF2D20%22%20fill-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('welcome.css') }}">
</head>

<body @if(!empty($backgroundImage)) style="--tv-bg-image: url('{{ $backgroundImage }}');" @endif>

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
                                <th>Tanggal dan Tempat</th>
                                <th>Nama Kegiatan</th>
                                <th>Disposisi</th>
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
                                    <div>{{ $date->translatedFormat('D, d M Y') }}</div>
                                    @if($jam)
                                    <div>{{ $jam->format('H.i') }}</div>
                                    @endif
                                    <div class="agenda-place">{{ $item->tempat ?? '-' }}</div>
                                </td>
                                <td class="agenda-nama">{{ $item->nama_kegiatan }}</td>
                                <td>{{ $item->disposisi }}</td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="3" class="text-start text-muted py-3">
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
            setInterval(tick, 60000);
        })();
    </script>

</body>

</html>

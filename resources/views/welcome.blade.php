<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=1920, height=1080, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

    <div class="container py-3">

        <div class="page-header mb-4 header-grid align-items-center">

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

        <div class="row tv-content-row mt-2 gx-1">

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
            <div class="col-12 col-md-6 tv-col">
                <!-- FIX: hapus h-100 -->
                <div class="card modern-card tv-card">
                    <div class="card-header bg-primary text-white modern-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-video me-2"></i> Video Kegiatan
                        </h5>
                    </div>

                    <!-- FIX: tv-card-body biar video bisa gede ngisi -->
                    <div class="card-body tv-card-body">
                        <div class="video-wrapper">
                            <video id="vidA" class="video-player" autoplay playsinline muted></video>
                            <video id="vidB" class="video-player" autoplay playsinline muted></video>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- AGENDA (NO CRUD) -->
        <div class="card modern-card mt-4 tv-agenda-card">
            <div class="card-header bg-primary text-white modern-card-header">
                <h5>
                    <i class="fas fa-calendar-check me-2"></i> Agenda Biro SDM
                </h5>
            </div>

            <div class="card-body">
                <!-- PAKAI WRAPPER ADMIN + TV MODE -->
                <div class="admin-table-wrapper agenda-scroll-container tv-mode" id="agendaScroll">
                    <table class="table table-hover mb-0">
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
                    {{ implode(' | ', $runningtext) }}
                </span>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CLOCK -->
    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');

            document.getElementById('timeText').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <!-- CAROUSEL -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const carouselElement = document.querySelector('#carouselPimpinan');
            if (carouselElement) {
                const carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 10000,
                    ride: 'carousel',
                    wrap: true
                });
            }
        });
    </script>

    <!-- VIDEO LOOP -->
    <script>
        (function() {
            const playlist = @json($playlist ?? []);
            let i = 0;

            // FIX: ambil element by id (biar gak ngandelin global var)
            const A = document.getElementById('vidA');
            const B = document.getElementById('vidB');

            if (!A || !B) return;
            if (!playlist.length) return;

            A.src = playlist[0];
            A.classList.add("active");
            A.play().catch(() => {});

            function next(cur, nxt) {
                i = (i + 1) % playlist.length;
                nxt.src = playlist[i];
                nxt.play().catch(() => {});
                cur.classList.remove("active");
                nxt.classList.add("active");
            }

            A.onended = () => next(A, B);
            B.onended = () => next(B, A);
        })();
    </script>

    <!-- ✅ UNLOCK AUDIO TANPA TOMBOL (klik/tap/remote OK pertama di mana aja) -->
    <script>
        (function() {
            const overlay = document.getElementById('unlockAudio');

            function unlock() {
                const A = document.getElementById('vidA');
                const B = document.getElementById('vidB');
                if (!A || !B) return;

                // aktifkan suara
                [A, B].forEach(v => {
                    v.muted = false;
                    v.volume = 1;
                });

                // play video aktif
                const active = A.classList.contains('active') ? A : B;
                active.play().catch(() => {});

                // MATIIN OVERLAY TOTAL
                if (overlay) overlay.classList.add('hidden');

                window.removeEventListener('click', unlock);
                window.removeEventListener('touchstart', unlock);
                window.removeEventListener('keydown', unlock);
            }

            window.addEventListener('click', unlock, {
                once: true
            });
            window.addEventListener('touchstart', unlock, {
                once: true
            });
            window.addEventListener('keydown', unlock, {
                once: true
            });
        })();
    </script>

    <!-- 🔥 AUTO SCROLL TV MODE - GRUP 4 ROW DENGAN FADE -->
    <script>
document.addEventListener("DOMContentLoaded", () => {
    const agendaScroll = document.getElementById("agendaScroll");
    const tbody = document.getElementById("agendaTbody");

    if (!agendaScroll || !tbody) return;

    const allRows = Array.from(tbody.querySelectorAll("tr"))
        .filter(r => !r.querySelector("td[colspan]"));

    if (!allRows.length) return;

    // =========================
    // PINNED (AGENDA HARI INI)
    // =========================
    const pinnedRows = allRows.filter(r => r.dataset.pinned === "true");
    const scrollRows = allRows.filter(r => r.dataset.pinned !== "true");

    const DISPLAY_TIME = 6000;
    const FADE_DURATION = 500;

    // RESET
    allRows.forEach(r => {
        r.style.display = "table-row";
        r.style.opacity = "1";
        r.style.transform = "none";
    });

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {

            const thead = agendaScroll.querySelector("thead");
            const theadHeight = thead ? thead.offsetHeight : 0;

            const pinnedHeight = pinnedRows.reduce((sum, r) => sum + r.offsetHeight, 0);

            const maxHeight =
                agendaScroll.clientHeight -
                theadHeight -
                pinnedHeight -
                6; // buffer

            // =========================
            // BUILD GROUPS (NON PINNED)
            // =========================
            const groups = [];
            let current = [];
            let height = 0;

            scrollRows.forEach(row => {
                const h = row.offsetHeight;

                if (h > maxHeight) {
                    if (current.length) groups.push(current);
                    groups.push([row]);
                    current = [];
                    height = 0;
                    return;
                }

                if (height + h > maxHeight && current.length) {
                    groups.push(current);
                    current = [];
                    height = 0;
                }

                current.push(row);
                height += h;
            });

            if (current.length) groups.push(current);

            // =========================
            // DISPLAY CONTROL
            // =========================

            // hide semua non-pinned
            scrollRows.forEach(r => {
                r.style.display = "none";
                r.style.opacity = "0";
            });

            // pinned selalu tampil
            pinnedRows.forEach(r => {
                r.style.display = "table-row";
                r.style.opacity = "1";
            });

            if (!groups.length) return;

            let index = 0;

            function showGroup(i) {
                scrollRows.forEach(r => r.style.opacity = "0");

                setTimeout(() => {
                    scrollRows.forEach(r => r.style.display = "none");

                    if (!groups[i]) return;

                    groups[i].forEach(r => {
                        r.style.display = "table-row";
                        r.style.opacity = "0";
                    });

                    requestAnimationFrame(() => {
                        groups[i].forEach(r => r.style.opacity = "1");
                    });

                }, FADE_DURATION);
            }

            showGroup(0);
            index = 1;

            setInterval(() => {
                showGroup(index);
                index = (index + 1) % groups.length;
            }, DISPLAY_TIME + FADE_DURATION);

        });
    });
});
</script>


    <!-- RUNNING TEXT -->
    <script>
        (function() {
            const raw = @json($runningtext ?? []);

            if (!raw.length) return;

            // Gabungkan semua text dengan separator |
            const mergedText = raw.join(' | ');

            const el = document.getElementById('runningText');
            if (!el) return;

            el.textContent = mergedText;

            // Jalankan animasi sekali terus looping via CSS animation
            el.style.animation = "none";
            void el.offsetWidth;
            el.style.animation = "marquee 25s linear infinite";
        })();
    </script>

    <!-- ✅ OVERLAY TRANSPARAN (tangkep 1x klik/tap/OK) -->
    <div id="unlockAudio"
        style="position:fixed; inset:0; z-index:9999; background:transparent;"></div>

</body>

</html>
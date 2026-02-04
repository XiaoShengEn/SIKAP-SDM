// TV page JS is loaded as an ES module via Vite. In module scope, globals like
// `bootstrap` are not guaranteed to exist as identifiers, so use `window.bootstrap`.
document.addEventListener('DOMContentLoaded', () => {
    /*.. CLOCK ..*/
    function updateClock() {
        const el = document.getElementById('timeText');
        if (!el) return;

        const now = new Date();
        const timeString = now
            .toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            })
            .replace(/\./g, ':');

        el.textContent = timeString;
    }

    updateClock();
    setInterval(updateClock, 1000);
});

/*.. CAROUSEL ..*/
document.addEventListener('DOMContentLoaded', () => {
    const carouselElement = document.querySelector('#carouselPimpinan');
    const bs = window.bootstrap;

    if (!carouselElement || !bs?.Carousel) return;

    // eslint-disable-next-line no-new
    new bs.Carousel(carouselElement, {
        interval: 10000,
        ride: 'carousel',
        wrap: true,
    });
});

    /*.. VIDEO LOOP ..*/
        (function() {
            const playlist = window.TV_DATA?.playlist || [];
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

    /*.. ✅ UNLOCK AUDIO TANPA TOMBOL (klik/tap/remote OK pertama di mana aja) ..*/
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

/* 🔥 AUTO SCROLL TV MODE — FIXED ROW PAGINATION (PINNED + CAROUSEL) */
document.addEventListener("DOMContentLoaded", () => {
    const agendaScroll = document.getElementById("agendaScroll");
    const tbody = document.getElementById("agendaTbody");

    if (!agendaScroll || !tbody) return;

    // AMBIL SEMUA ROW VALID
    const allRows = Array.from(tbody.querySelectorAll("tr"))
        .filter(r => !r.querySelector("td[colspan]"));

    if (!allRows.length) return;

    // =========================
    // CONFIG
    // =========================
    const TOTAL_ROWS = 6;        // TOTAL BARIS DI LAYAR
    const DISPLAY_TIME = 6000;
    const FADE_DURATION = 400;

    // =========================
    // PINNED & CAROUSEL
    // =========================
    const pinnedRows = allRows.filter(r => r.dataset.pinned === "true");
    const carouselRows = allRows.filter(r => r.dataset.pinned !== "true");

    // SLOT CAROUSEL (ANTI GOBLOK)
    const CAROUSEL_ROWS_PER_PAGE = Math.max(
        TOTAL_ROWS - pinnedRows.length,
        1
    );

    // =========================
    // RESET STYLE
    // =========================
    allRows.forEach(r => {
        r.style.display = "none";
        r.style.opacity = "0";
        r.style.transition = `opacity ${FADE_DURATION}ms ease`;
    });

    // PINNED SELALU TAMPIL
    pinnedRows.forEach(r => {
        r.style.display = "table-row";
        r.style.opacity = "1";
    });

    // =========================
    // BUILD PAGES (CAROUSEL)
    // =========================
    const pages = [];
    for (let i = 0; i < carouselRows.length; i += CAROUSEL_ROWS_PER_PAGE) {
        pages.push(carouselRows.slice(i, i + CAROUSEL_ROWS_PER_PAGE));
    }

    if (!pages.length) return;

    // =========================
    // CAROUSEL LOGIC
    // =========================
    let index = 0;

    function showPage(i) {
        // FADE OUT SEMUA CAROUSEL
        carouselRows.forEach(r => r.style.opacity = "0");

        setTimeout(() => {
            // SEMBUNYIKAN SEMUA
            carouselRows.forEach(r => r.style.display = "none");

            // TAMPILKAN PAGE AKTIF
            pages[i].forEach(r => {
                r.style.display = "table-row";
                r.style.opacity = "0";
            });

            requestAnimationFrame(() => {
                pages[i].forEach(r => r.style.opacity = "1");
            });

        }, FADE_DURATION);
    }

    // TAMPILKAN PAGE PERTAMA
 showPage(index);

// JALANKAN CAROUSEL HANYA JIKA LEBIH DARI 1 PAGE
if (pages.length > 1) {
    setInterval(() => {
        index = (index + 1) % pages.length;
        showPage(index);
    }, DISPLAY_TIME + FADE_DURATION);
}
});


    /*.. RUNNING TEXT ..*/
        (function() {
            const raw = window.TV_DATA?.runningtext || [];
            const cleaned = raw
                .map(v => String(v ?? '').trim())
                .filter(Boolean);

            const el = document.getElementById('runningText');
            if (!el) return;

            const mergedText = cleaned.length ? cleaned.join(' | ') : '-';
            el.textContent = mergedText;

            // Jalankan animasi sekali terus looping via CSS animation
            el.style.animation = "none";
            void el.offsetWidth;
            el.style.animation = "marquee 25s linear infinite";
        })();

/* ================= TV DELAYED UPDATE (DEBOUNCE) ================= */

let updateTimer = null;
const DELAY = 60000; // 1 menit (ubah di sini kalau mau)

window.addEventListener('agenda-updated', () => {
    console.log('📡 Agenda update received → start 1 min timer');

    // Kalau timer sudah jalan, abaikan event berikutnya
    if (updateTimer) return;

    updateTimer = setTimeout(() => {
        console.log('🔄 TV reload after delay');
        location.reload();
    }, DELAY);
});

// Generic TV refresh signal (profil/video/runningtext/admin/etc).
// Also used by agenda via resources/js/app.js.
let tvRefreshTimer = null;
const TV_REFRESH_DELAY_MS = 60000; // 1 menit

window.addEventListener('tv-refresh', () => {
    console.log('📡 Update incoming → start 1 min timer');

    if (tvRefreshTimer) clearTimeout(tvRefreshTimer);

    tvRefreshTimer = setTimeout(() => {
        console.log('🔄 TV reload after 1 min delay');
        location.reload();
    }, TV_REFRESH_DELAY_MS);
});

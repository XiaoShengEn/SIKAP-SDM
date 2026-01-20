    /*.. CLOCK ..*/
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

    /*.. CAROUSEL ..*/
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

    /*.. 🔥 AUTO SCROLL TV MODE - GRUP 4 ROW DENGAN FADE ..*/
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

    /*.. RUNNING TEXT ..*/
        (function() {
            const raw = window.TV_DATA?.runningtext || [];

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

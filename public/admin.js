/* =================================
    SIDEBAR NAVIGATION 
    Buka/tutup sidebar
    Auto close di HP
    Highlight menu sesuai posisi scroll
    Smooth scroll saat klik menu
 ================================= */

        (function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const menuBtn = document.getElementById('menuBtn');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                if (window.innerWidth > 992) content.classList.toggle('with-sidebar');
            }

            menuBtn.addEventListener('click', toggleSidebar);

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    if (!sidebar.contains(e.target) && !menuBtn.contains(e.target) && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('show');
                    content.classList.remove('with-sidebar');
                }
            });

            const sidebarItems = document.querySelectorAll('.sidebar-item');

            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');

                    // ===== ACTIVE =====
                    sidebarItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');

                    if (targetId.startsWith('#')) {
                        e.preventDefault();
                        const targetSection = document.querySelector(targetId);
                        if (targetSection) {

                            // ===== AUTO OPEN COLLAPSE =====
                            const collapseBody = targetSection.querySelector('.collapse');
                            if (collapseBody) {
                                const instance = bootstrap.Collapse.getOrCreateInstance(collapseBody, {
                                    toggle: false
                                });
                                instance.show();
                                localStorage.setItem("open-section", collapseBody.id);
                            }

                            window.scrollTo({
                                top: targetSection.offsetTop - 100,
                                behavior: 'smooth'
                            });
                        }
                    }

                    if (window.innerWidth <= 992) sidebar.classList.remove('show');
                });
            });

        })();

    /* ================================= 
    REMEMBER LAST COLLAPSE
    Menyimpan panel yang terakhir dibuka
    Saat reload tetap terbuka
    ================================= */
        document.addEventListener("DOMContentLoaded", function() {

            const STORAGE_KEY = "open-section";

            // ================= RESTORE SAAT RELOAD =================
            const lastOpen = localStorage.getItem(STORAGE_KEY);
            if (lastOpen) {
                const el = document.getElementById(lastOpen);
                if (el) {
                    const instance = bootstrap.Collapse.getOrCreateInstance(el, {
                        toggle: false
                    });
                    instance.show();

                    // sync sidebar
                    const sidebarItem = document.querySelector(`.sidebar-item[data-target="${el.id}"]`);
                    if (sidebarItem) {
                        document.querySelectorAll('.sidebar-item').forEach(a => a.classList.remove('active'));
                        sidebarItem.classList.add('active');
                    }
                }
            }

            // ================= HANDLE COLLAPSE =================
            document.querySelectorAll('.collapse').forEach(collapseEl => {

                collapseEl.addEventListener('show.bs.collapse', function() {

                    // tutup yang lain
                    document.querySelectorAll('.collapse.show').forEach(openEl => {
                        if (openEl !== this) {
                            bootstrap.Collapse.getOrCreateInstance(openEl, {
                                toggle: false
                            }).hide();
                        }
                    });

                    // simpan
                    localStorage.setItem(STORAGE_KEY, this.id);

                    // sync sidebar
                    const sidebarItem = document.querySelector(`.sidebar-item[data-target="${this.id}"]`);
                    if (sidebarItem) {
                        document.querySelectorAll('.sidebar-item').forEach(a => a.classList.remove('active'));
                        sidebarItem.classList.add('active');
                    }

                });

                collapseEl.addEventListener('hidden.bs.collapse', function() {
                    if (localStorage.getItem(STORAGE_KEY) === this.id) {
                        localStorage.removeItem(STORAGE_KEY);
                    }
                });

            });

        });

    /* ================================= 
    GLOBAL PAGINATION SYSTEM
    Dipakai oleh:
    - Profil
    - Video
    - Agenda
    - Running Text
    - Normal Admin
    
    Fungsi:
    - Pagination
    - Search
    - Menjaga tinggi tabel stabil (row kosong)
    ================================= */
        const rowsPerPageMap = {
            profil: 3,
            video: 2,
            agenda: 4,
            runningtext: 6,
            normaladmin: 4,
        };

        function initTablePagination(tableName) {
            const tbody = document.getElementById(tableName + "Tbody");
            const searchInput = document.getElementById(tableName + "Search");
            const prevBtn = document.getElementById(tableName + "PrevBtn");
            const nextBtn = document.getElementById(tableName + "NextBtn");

            if (!tbody) return;

            const allRows = Array.from(tbody.querySelectorAll("tr"));
            const allDataRows = allRows.filter(row => {
                const firstCell = row.querySelector("td");
                return firstCell && !firstCell.hasAttribute("colspan");
            });

            let filteredRows = [...allDataRows];
            let currentPage = 0;
            window.__paginationState = window.__paginationState || {};
            const rowsPerPage = rowsPerPageMap[tableName] || 4;

            const emptyRowClass = tableName + "-empty-row";
            const existingEmptyRows = Array.from(tbody.querySelectorAll("." + emptyRowClass));
            const firstDataRow = allDataRows[0];
            const colCount = firstDataRow ? firstDataRow.querySelectorAll("td").length : 6;

            if (existingEmptyRows.length < rowsPerPage) {
                const rowsToAdd = rowsPerPage - existingEmptyRows.length;
                for (let i = 0; i < rowsToAdd; i++) {
                    const emptyRow = document.createElement("tr");
                    emptyRow.className = emptyRowClass;
                    emptyRow.innerHTML = `<td colspan="${colCount}" style="height:60px;border:none;"></td>`;
                    tbody.appendChild(emptyRow);
                }
            }

            const emptyRows = Array.from(tbody.querySelectorAll("." + emptyRowClass));

            function showPage(page) {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (page < 0) page = 0;
                if (page >= totalPages && totalPages > 0) page = totalPages - 1;

                currentPage = page;
                window.__paginationState[tableName] = currentPage;


                allDataRows.forEach(r => r.style.display = 'none');
                emptyRows.forEach(r => r.style.display = 'none');

                const start = currentPage * rowsPerPage;
                const end = start + rowsPerPage;
                const visible = filteredRows.slice(start, end);

                visible.forEach(r => r.style.display = 'table-row');

                const filler = emptyRows.slice(0, rowsPerPage - visible.length);
                filler.forEach(r => r.style.display = 'table-row');

                if (prevBtn && nextBtn) {
                    prevBtn.disabled = currentPage === 0;
                    nextBtn.disabled = currentPage >= totalPages - 1 || totalPages === 0;
                }
            }

            function performSearch() {
                const term = searchInput ? searchInput.value.toLowerCase().trim() : '';

                filteredRows = term === '' ? [...allDataRows] :
                    allDataRows.filter(row =>
                        (row.getAttribute('data-search') || '').includes(term)
                    );

                currentPage = 0;
                showPage(0);
            }

            if (searchInput) searchInput.addEventListener('input', performSearch);
            if (prevBtn) prevBtn.addEventListener('click', () => showPage(currentPage - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages - 1) showPage(currentPage + 1);
            });

            const savedPage = sessionStorage.getItem(tableName + "_lastPage");

            if (savedPage !== null) {
                showPage(parseInt(savedPage));
                sessionStorage.removeItem(tableName + "_lastPage");
            } else {
                showPage(0);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tables = ['profil', 'video', 'agenda', 'runningtext', 'normaladmin'];

            tables.forEach(name => {
                initTablePagination(name);

                const clearBtn = document.getElementById(name + 'ClearSearch');
                const searchInput = document.getElementById(name + 'Search');

                if (clearBtn && searchInput) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        initTablePagination(name);
                    });
                }
            });
        });

    /* =================================
    Biar Setelah Edit Tidak Balik Ke Awal
    ================================= */
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll("form").forEach(form => {

        form.addEventListener("submit", () => {

            let tableName = null;

            // cari tbody terdekat yang id-nya berakhiran "Tbody"
            const modal = form.closest(".modal");

            if (modal) {

                const allTbodies = document.querySelectorAll("tbody[id$='Tbody']");

                allTbodies.forEach(tbody => {

                    const table = tbody.closest("table");
                    if (!table) return;

                    // kalau tombol edit di tabel ini yang buka modal ini
                    const editBtn = table.querySelector(
                        `[data-bs-target="#${modal.id}"]`
                    );

                    if (editBtn) {
                        tableName = tbody.id.replace("Tbody", "");
                    }

                });

            }

            if (
                tableName &&
                window.__paginationState &&
                window.__paginationState[tableName] !== undefined
            ) {
                sessionStorage.setItem(
                    tableName + "_lastPage",
                    window.__paginationState[tableName]
                );
            }

        });

    });

});



    /* =================================
    VIDEO SECTION
    Counter textarea modal tambah video
    Counter textarea edit video
    ================================= */
        const videoModalTextarea = document.getElementById('video_keterangan_modal');
        const videoModalCounter = document.getElementById('videoModalCounter');

        if (videoModalTextarea) {
            videoModalTextarea.addEventListener('input', () => {
                videoModalCounter.innerText = videoModalTextarea.value.length;
            });
        }

        document.querySelectorAll('.video-edit-textarea').forEach(textarea => {
            const counterEl = document.getElementById(textarea.dataset.counter);
            textarea.addEventListener('input', () => {
                counterEl.innerText = textarea.value.length;
            });
        });

    /* ================================= 
    = = = = 9. PASSWORD VISIBILITY TOGGLE = =
    =  - Show / hide password
    ================================= */
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.password-toggle');
            if (!btn) return;

            const input = document.getElementById(btn.dataset.target);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.classList.toggle('active', isPassword);
        });

    /* =================================
    GLOBAL MAXLENGTH COUNTER
    Semua input/textarea dengan maxlength
    Menampilkan counter
    ================================= */
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[maxlength], textarea[maxlength]').forEach(input => {
                const max = parseInt(input.getAttribute('maxlength'));
                if (!max) return;

                const counter = document.createElement('small');
                counter.className = 'text-muted d-block text-start counter-tight';

                // 🔑 CEK APAKAH INPUT ADA DI INPUT-GROUP
                const inputGroup = input.closest('.input-group');

                if (inputGroup) {
                    inputGroup.after(counter); // TARUH SETELAH INPUT-GROUP
                } else {
                    input.after(counter); // NORMAL
                }

                function updateCounter() {
                    let length = input.value.length;
                    if (length > max) {
                        input.value = input.value.slice(0, max);
                        length = max;
                    }

                    counter.innerText = `${length}/${max} karakter`;
                    counter.style.color = length >= max ? 'red' : '';
                }

                input.addEventListener('input', updateCounter);
                updateCounter();
            });
        });

    /* =================================
     AUTO LOGOUT BERDASARKAN KETIDAKAKTIFAN USER
     ------------------------------------------------------------
     Fungsi:
     - Menghitung waktu tidak ada aktivitas user
     - Jika melewati batas waktu, user otomatis logout
     
     Aktivitas yang dianggap aktif:
     - Mouse bergerak
     - Keyboard ditekan
     - Click
     - Scroll
     - Touch (untuk device mobile)
    ================================= */

        let autoLogoutTimer;
        const AUTO_LOGOUT_INTERVAL = 5 * 60 * 1000; // Waktu tidak aktif sebelum logout (5 menit)

        function resetAutoLogoutTimer() {
            clearTimeout(autoLogoutTimer);
            autoLogoutTimer = setTimeout(() => {
                window.location.href = "{{ url('/logout') }}";
            }, AUTO_LOGOUT_INTERVAL);
        }

        // Aktivitas yang dianggap sebagai aktivitas pengguna
        window.addEventListener('mousemove', resetAutoLogoutTimer);
        window.addEventListener('keydown', resetAutoLogoutTimer);
        window.addEventListener('click', resetAutoLogoutTimer);
        window.addEventListener('scroll', resetAutoLogoutTimer);
        window.addEventListener('touchstart', resetAutoLogoutTimer);

        // Mulai timer pertama kali
        resetAutoLogoutTimer();


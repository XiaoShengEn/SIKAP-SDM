/* =================================
SIDEBAR NAVIGATION 
Buka/tutup sidebar
Auto close di HP
Highlight menu sesuai posisi scroll
Smooth scroll saat klik menu
 ================================= */
(function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const menuBtn = document.getElementById('menuBtn');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        if (window.innerWidth > 992) content.classList.toggle('with-sidebar');
    }

    menuBtn.addEventListener('click', toggleSidebar);

    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 992) {
            if (!sidebar.contains(e.target) && !menuBtn.contains(e.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('show');
            content.classList.remove('with-sidebar');
        }
    });

    const sidebarItems = document.querySelectorAll('.sidebar-item');

    sidebarItems.forEach(item => {
        item.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');

            // ===== ACTIVE =====

            if (targetId.startsWith('#')) {
                e.preventDefault();
                const targetSection = document.querySelector(targetId);
                if (targetSection) {

                    // ===== SIDEBAR TOGGLE (SAMA KAYAK HEADER) =====
                    const collapseBody = targetSection.querySelector('.collapse');

                    if (collapseBody) {
                        const instance = bootstrap.Collapse.getOrCreateInstance(collapseBody, {
                            toggle: false
                        });

                        // tutup semua dulu
                        document.querySelectorAll('.collapse.show').forEach(openEl => {
                            if (openEl !== collapseBody) {
                                bootstrap.Collapse.getOrCreateInstance(openEl, { toggle: false }).hide();
                            }
                        });

                        // toggle target
                        if (collapseBody.classList.contains('show')) {
                            instance.hide();
                            localStorage.removeItem("open-section");
                        } else {
                            instance.show();
                            localStorage.setItem("open-section", collapseBody.id);
                        }
                        // ✅ AUTO SCROLL (SETELAH TOGGLE)
                        setTimeout(() => {
                            window.scrollTo({
                                top: targetSection.offsetTop - 100,
                                behavior: 'smooth'
                            });
                        }, 250); // nunggu animasi bootstrap
                    }

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
document.addEventListener("DOMContentLoaded", function () {

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

        collapseEl.addEventListener('shown.bs.collapse', function () {

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

        collapseEl.addEventListener('hidden.bs.collapse', function () {
            if (localStorage.getItem(STORAGE_KEY) === this.id) {
                localStorage.removeItem(STORAGE_KEY);
            }
        });

        // ================= RESTORE SCROLL + COLLAPSE AFTER CRUD =================
        const lastSection = sessionStorage.getItem("lastSection");
        const lastCollapse = sessionStorage.getItem("lastCollapse");

        if (lastCollapse) {
            const el = document.getElementById(lastCollapse);
            if (el) {
                bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).show();
            }
        }

        if (lastSection) {
            const section = document.getElementById(lastSection);

            if (section) {
                setTimeout(() => {
                    const yOffset = -90;
                    const y = section.getBoundingClientRect().top + window.pageYOffset + yOffset;

                    window.scrollTo({
                        top: y,
                        behavior: "smooth"
                    });
                }, 450);
            }

            sessionStorage.removeItem("lastSection");
            sessionStorage.removeItem("lastCollapse");
        }


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
    runningtext: 4,
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

document.addEventListener('DOMContentLoaded', function () {
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
Biar Setelah CRUD Tidak Balik Ke Awal
================================= */
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll("form").forEach(form => {

        form.addEventListener("submit", () => {

            let tableName = null;
            let sectionId = null;

            const modal = form.closest(".modal");

            // ===== CASE: EDIT VIA MODAL =====
            if (modal) {

                const allTbodies = document.querySelectorAll("tbody[id$='Tbody']");

                allTbodies.forEach(tbody => {

                    const table = tbody.closest("table");
                    if (!table) return;

                    const editBtn = table.querySelector(
                        `[data-bs-target="#${modal.id}"]`
                    );

                    if (editBtn) {
                        tableName = tbody.id.replace("Tbody", "");
                        sectionId = tbody.closest("section")?.id;
                    }

                });

            }

            // ===== CASE: TAMBAH / DELETE (NON MODAL) =====
            if (!sectionId) {
                const section = form.closest("section");
                if (section) {
                    sectionId = section.id;
                }
            }

            // ===== SAVE PAGINATION =====
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

            // ===== SAVE SECTION + COLLAPSE =====
            if (sectionId) {
                sessionStorage.setItem("lastSection", sectionId);

                const section = document.getElementById(sectionId);
                const collapse =
                    section?.querySelector(".collapse.show") ||
                    section?.querySelector(".collapse");

                if (collapse) {
                    sessionStorage.setItem("lastCollapse", collapse.id);
                    localStorage.setItem("open-section", collapse.id);
                }
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
document.addEventListener('click', function (e) {
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
document.addEventListener('DOMContentLoaded', function () {
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
CARD HEADER NAVIGATION
- Auto close other collapse
- Smooth scroll like sidebar
================================= */
document.querySelectorAll('.modern-card-header').forEach(header => {
    header.addEventListener('click', function () {
        const targetSelector = this.getAttribute('data-bs-target');
        const targetCollapse = document.querySelector(targetSelector);
        const section = this.closest('section');

        // === Tutup collapse lain ===
        document.querySelectorAll('.collapse.show').forEach(openEl => {
            if (openEl !== targetCollapse) {
                bootstrap.Collapse.getOrCreateInstance(openEl, { toggle: false }).hide();
            }
        });

        // === Buka target ===
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(targetCollapse, {
            toggle: false
        });

        if (!targetCollapse.classList.contains('show')) {
            bsCollapse.show();
        }

        // === Auto Scroll kayak sidebar ===
        if (section) {
            setTimeout(() => {
                const yOffset = -90;
                const y = section.getBoundingClientRect().top + window.pageYOffset + yOffset;

                window.scrollTo({
                    top: y,
                    behavior: 'smooth'
                });
            }, 200);
        }
    });
});

/* =================================
 AUTO LOGOUT BERDASARKAN KETIDAKAKTIFAN USER (FIXED)
================================= */
const AUTO_LOGOUT_INTERVAL = 5 * 60 * 1000;
let autoLogoutTimer;

function resetAutoLogoutTimer() {
    clearTimeout(autoLogoutTimer);

    autoLogoutTimer = setTimeout(() => {
        fetch("/logout", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        }).finally(() => {
            window.location.href = "/login";
        });
    }, AUTO_LOGOUT_INTERVAL);
}

["mousemove", "keydown", "click", "scroll", "touchstart"]
    .forEach(e => document.addEventListener(e, resetAutoLogoutTimer, true));

resetAutoLogoutTimer();


/* =================================
   AGENDA KEGIATAN - AJAX CRUD
   - Load data dengan pagination
   - Tambah agenda (AJAX)
   - Edit agenda (AJAX)
   - Delete agenda (AJAX)
   - Search & Filter
================================= */

document.addEventListener("DOMContentLoaded", function () {

    const tbody = document.getElementById("kegiatan-body");
    const searchInput = document.getElementById("agendaSearch");
    const clearSearchBtn = document.getElementById("agendaClearSearch");
    const prevBtn = document.getElementById("agendaPrevBtn");
    const nextBtn = document.getElementById("agendaNextBtn");
    const totalBadge = document.getElementById("agendaTotalBadge");

    // Form elements
    const formTambah = document.getElementById("form-kegiatan");
    const formEdit = document.getElementById("form-edit-kegiatan");
    const modalEditAgenda = document.getElementById("modalEditAgenda");
    const modalTambahAgenda = document.getElementById("modalTambahAgenda");

    // Edit form inputs
    const edit_id = document.getElementById("edit_id");
    const edit_tanggal = document.getElementById("edit_tanggal");
    const edit_jam = document.getElementById("edit_jam");
    const edit_nama = document.getElementById("edit_nama");
    const edit_tempat = document.getElementById("edit_tempat");
    const edit_disposisi = document.getElementById("edit_disposisi");
    const edit_keterangan = document.getElementById("edit_keterangan");

    let currentPage = 1;
    let searchTerm = "";

    /* ===== GET CSRF TOKEN ===== */
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    /* ===== WARNA TANGGAL BERDASARKAN HARI ===== */
    function getTanggalClass(tanggalStr) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const target = new Date(tanggalStr);
        target.setHours(0, 0, 0, 0);

        const diff = (target - today) / 86400000;

        if (diff === 0) return "agenda-today";
        if (diff === 1) return "agenda-tomorrow";
        if (diff >= 2) return "agenda-other";
        return "";
    }

    /* ===== LOAD DATA AGENDA ===== */
    async function loadKegiatan(page = 1) {
        currentPage = page;

        try {
            const res = await fetch(`/superadmin/kegiatan/list?page=${page}`);
            if (!res.ok) throw new Error("HTTP " + res.status);

            const result = await res.json();
            const data = result.data || [];
            const total = result.total || 0;

            // Update badge total
            if (totalBadge) {
                totalBadge.textContent = `${total} Data`;
            }

            tbody.innerHTML = "";

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">Belum ada agenda</td></tr>`;
                if (prevBtn) prevBtn.disabled = true;
                if (nextBtn) nextBtn.disabled = true;
                return;
            }

            data.forEach(k => {
                const row = document.createElement("tr");
                row.className = getTanggalClass(k.tanggal_kegiatan);

                row.setAttribute("data-search",
                    `${k.tanggal_label} ${k.nama_kegiatan} ${k.tempat || ''} ${k.disposisi || ''} ${k.keterangan || ''}`.toLowerCase()
                );

                const jamClean = k.jam ? k.jam.substring(0, 5) : "";

                row.innerHTML = `
                    <td>
                        <div>${k.tanggal_label ?? k.tanggal_kegiatan}</div>
                        ${jamClean ? `<div class="small">${jamClean} WIB</div>` : ""}
                    </td>
                    <td>${k.nama_kegiatan}</td>
                    <td>${k.tempat || "-"}</td>
                    <td>${k.disposisi || "-"}</td>
                    <td>${k.keterangan || "-"}</td>
                    <td class="td-aksi">
                        <div class="aksi-group">
                            <button class="btn btn-sm btn-warning edit-btn" data-id="${k.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${k.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // Update navigation buttons
            if (prevBtn) prevBtn.disabled = result.current_page <= 1;
            if (nextBtn) nextBtn.disabled = result.current_page >= result.last_page;

// Apply search filter if active
if (searchTerm) {
    performSearch();
}

// RESET STATE VISUAL LAMA AGAR REALTIME TERLIHAT
searchTerm = "";
currentPage = 1;

tbody.querySelectorAll("tr").forEach(r => {
    r.style.display = "";
});

        } catch (e) {
            console.error("Error loading agenda:", e);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger">Gagal memuat data</td></tr>`;
        }
    }

    /* ===== SEARCH FUNCTION ===== */
    function performSearch() {
        const term = searchTerm.toLowerCase().trim();
        const rows = tbody.querySelectorAll("tr[data-search]");

        rows.forEach(row => {
            const searchData = row.getAttribute("data-search");
            row.style.display = searchData.includes(term) ? "" : "none";
        });
    }

    /* ===== EVENT: SEARCH INPUT ===== */
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            searchTerm = this.value;
            performSearch();
        });
    }

    /* ===== EVENT: CLEAR SEARCH ===== */
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener("click", function () {
            searchInput.value = "";
            searchTerm = "";
            performSearch();
        });
    }

    /* ===== EVENT: PAGINATION BUTTONS ===== */
    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            if (currentPage > 1) {
                loadKegiatan(currentPage - 1);
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            loadKegiatan(currentPage + 1);
        });
    }

    /* ===== SUBMIT TAMBAH AGENDA ===== */
    if (formTambah) {
        formTambah.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const res = await fetch("/superadmin/kegiatan/store", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken()
                    },
                    body: formData
                });

                const result = await res.json();

                if (!res.ok || !result.success) {
                    throw new Error(result.message || "Gagal menambahkan agenda");
                }

                // Close modal
                const modalInstance = bootstrap.Modal.getInstance(modalTambahAgenda);
                if (modalInstance) modalInstance.hide();

                // Reset form
                this.reset();

                // Reload data
                currentPage = 1;
                loadKegiatan(1);

                // Show success message (optional)
                alert("Agenda berhasil ditambahkan!");

            } catch (error) {
                console.error("Error:", error);
                alert(error.message || "Terjadi kesalahan saat menambahkan agenda");
            }
        });
    }

    /* ===== CLICK EDIT & DELETE BUTTONS ===== */
    if (tbody) {
        tbody.addEventListener("click", async function (e) {

            // HANDLE EDIT
            const editBtn = e.target.closest(".edit-btn");
            if (editBtn) {
                const id = editBtn.dataset.id;

                try {
                    const res = await fetch(`/superadmin/kegiatan/${id}`);
                    if (!res.ok) throw new Error("Gagal mengambil data");

                    const k = await res.json();

                    // Fill form
                    edit_id.value = k.kegiatan_id;
                    edit_tanggal.value = k.tanggal_kegiatan;
                    edit_jam.value = k.jam || "";
                    edit_nama.value = k.nama_kegiatan;
                    edit_tempat.value = k.tempat || "";
                    edit_disposisi.value = k.disposisi || "";
                    edit_keterangan.value = k.keterangan || "";

                    // Show modal
                    const modalInstance = new bootstrap.Modal(modalEditAgenda);
                    modalInstance.show();

                } catch (error) {
                    console.error("Error:", error);
                    alert("Gagal mengambil data agenda");
                }
            }

            // HANDLE DELETE
            const deleteBtn = e.target.closest(".delete-btn");
            if (deleteBtn) {
                const id = deleteBtn.dataset.id;

                if (!confirm("Yakin ingin menghapus agenda ini?")) return;

                try {
                    const res = await fetch(`/superadmin/kegiatan/${id}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": getCsrfToken()
                        }
                    });

                    const result = await res.json();

                    if (!res.ok || !result.success) {
                        throw new Error("Gagal menghapus agenda");
                    }

                    loadKegiatan(currentPage);
                    alert("Agenda berhasil dihapus!");

                } catch (error) {
                    console.error("Error:", error);
                    alert("Gagal menghapus agenda");
                }
            }
        });
    }

    /* ===== SUBMIT EDIT AGENDA ===== */
    if (formEdit) {
        formEdit.addEventListener("submit", async function (e) {
            e.preventDefault();

            const id = edit_id.value;
            const formData = new FormData(this);

            try {
                const res = await fetch(`/superadmin/kegiatan/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken()
                    },
                    body: formData
                });

                const result = await res.json();

                if (!res.ok || !result.success) {
                    throw new Error(result.message || "Gagal mengupdate agenda");
                }

                // Close modal
                const modalInstance = bootstrap.Modal.getInstance(modalEditAgenda);
                if (modalInstance) modalInstance.hide();

                // Reload data
                loadKegiatan(currentPage);

                alert("Agenda berhasil diperbarui!");

            } catch (error) {
                console.error("Error:", error);
                alert(error.message || "Gagal mengupdate agenda");
            }
        });
    }

    /* ================================
     INITIALIZE
    ================================ */
    loadKegiatan(1);
    loadNormalAdmin();

    /* ================================
       LOAD NORMAL ADMIN
    ================================ */
    async function loadNormalAdmin() {
        try {
            const res = await fetch("/superadmin/normaladmin/list");
            if (!res.ok) throw new Error("Gagal load admin");

            const result = await res.json();
            const tbody = document.getElementById("normaladminTbody");
            if (!tbody) return;

            tbody.innerHTML = "";

            result.data.forEach(a => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${a.nama_admin}</td>
                    <td>${a.bagian}</td>
                    <td class="td-aksi">

                        <button type="button"
                            class="btn btn-sm btn-warning btn-edit-admin"
                            data-id="${a.id_admin}"
                            data-nip="${a.nip}"
                            data-nama="${a.nama_admin}"
                            data-bagian="${a.bagian}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button type="button"
                            class="btn btn-sm btn-danger btn-delete-admin"
                            data-id="${a.id_admin}">
                            <i class="fas fa-trash"></i>
                        </button>

                    </td>
                `;

                tbody.appendChild(tr);
            });

        } catch (err) {
            console.error("LOAD ADMIN ERROR:", err);
        }
    }

    /* ================================
       SHOW + CLEAR VALIDATION ERRORS
    ================================ */
    function showErrors(form, errors) {
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input) return;

            input.classList.add("is-invalid");

            const div = document.createElement("div");
            div.className = "invalid-feedback d-block";
            div.innerText = errors[field][0];

            input.parentNode.appendChild(div);
        });
    }

    function clearErrors(form) {
        form.querySelectorAll(".is-invalid").forEach(el => {
            el.classList.remove("is-invalid");
        });

        form.querySelectorAll(".invalid-feedback").forEach(el => {
            el.remove();
        });
    }

    /* ================================
       SUBMIT TAMBAH ADMIN
    ================================ */
    const formTambahAdmin = document.getElementById("formTambahAdmin");

    if (formTambahAdmin) {
        formTambahAdmin.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const res = await fetch("/superadmin/normaladmin/store", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content")
                    },
                    body: formData
                });

                const result = await res.json();
                console.log("RESULT TAMBAH:", result);

                if (res.status === 422) {
                    alert("Validasi gagal!");
                    return;
                }

                if (!res.ok || !result.success) {
                    alert("Tambah admin gagal!");
                    return;
                }

                // ✅ MODAL CLOSE FIX
                const modalEl = document.getElementById("modalTambahNormalAdmin");
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();

                this.reset();
                loadNormalAdmin();

                alert("Admin berhasil ditambahkan!");

            } catch (err) {
                console.error("ERROR TAMBAH:", err);
                alert("Error tambah admin");
            }
        });
    }

    /* ================================
       OPEN EDIT MODAL
    ================================ */
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-edit-admin");
        if (!btn) return;

        document.getElementById("edit_id_admin").value = btn.dataset.id;
        document.getElementById("edit_nip").value = btn.dataset.nip;
        document.getElementById("edit_nama_admin").value = btn.dataset.nama;
        document.getElementById("edit_bagian").value = btn.dataset.bagian;

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById("modalEditNormalAdmin")
        ).show();
    });

    /* ================================
       SUBMIT EDIT ADMIN
    ================================ */
    const formEditAdmin = document.getElementById("formEditAdmin");

    if (formEditAdmin) {
        formEditAdmin.addEventListener("submit", async function (e) {
            e.preventDefault();

            clearErrors(this);

            const id = document.getElementById("edit_id_admin").value;
            const formData = new FormData(this);

            try {
                const res = await fetch(`/superadmin/normaladmin/update/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken()
                    },
                    body: formData
                });

                const result = await res.json();

                if (res.status === 422) {
                    showErrors(this, result.errors);
                    return;
                }

                if (!res.ok || !result.success) {
                    alert("Gagal update admin!");
                    return;
                }

                // ✅ CLOSE MODAL SAFE
                const modalEl = document.getElementById("modalEditNormalAdmin");
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();

                loadNormalAdmin();
                alert("Admin berhasil diperbarui!");

            } catch (err) {
                console.error("EDIT ERROR:", err);
                alert("Error update admin");
            }
        });
    }

    /* ================================
       DELETE ADMIN
    ================================ */
    document.addEventListener("click", async function (e) {
        const btn = e.target.closest(".btn-delete-admin");
        if (!btn) return;

        const id = btn.dataset.id;
        if (!confirm("Yakin hapus admin?")) return;

        try {
            const res = await fetch(`/superadmin/normaladmin/delete/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken()
                }
            });

            const result = await res.json();

            if (!res.ok || !result.success) {
                alert("Gagal hapus admin!");
                return;
            }

            loadNormalAdmin();
            alert("Admin berhasil dihapus!");

        } catch (err) {
            console.error("DELETE ERROR:", err);
            alert("Error hapus admin");
        }
    });

});

/* =================================
REALTIME AGENDA LISTENER (REVERB)
================================= */
document.addEventListener("DOMContentLoaded", function () {

    if (!window.Echo) {
        console.warn("Echo belum tersedia");
        return;
    }

    window.Echo.channel("agenda-channel")
        .listen(".agenda.updated", (e) => {
            console.log("Realtime agenda update:", e);

            if (typeof loadKegiatan === "function") {
                loadKegiatan(1);
            }
        });
});
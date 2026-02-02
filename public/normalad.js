document.addEventListener("DOMContentLoaded", function () {

    /* ================= STATE ================= */
    let currentPage = 1;
    let lastPage = 1;
    let searchTerm = "";
    let isLoading = false;

    /* ================= ELEMENT ================= */
    const tbody = document.getElementById("agendaTbody");
    const searchInput = document.getElementById("agendaSearch");
    const clearSearchBtn = document.getElementById("agendaClearSearch");
    const prevBtn = document.getElementById("agendaPrevBtn");
    const nextBtn = document.getElementById("agendaNextBtn");
    const totalBadge = document.getElementById("agendaTotalBadge");

    const formTambah = document.getElementById("form-tambah-agenda");
    const formEdit = document.getElementById("form-edit-agenda");
    const modalEditAgenda = document.getElementById("modalEditAgenda");
    const modalTambahAgenda = document.getElementById("modalTambahAgenda");

    const edit_id = document.getElementById("edit_id");
    const edit_tanggal = document.getElementById("edit_tanggal");
    const edit_jam = document.getElementById("edit_jam");
    const edit_nama = document.getElementById("edit_nama");
    const edit_tempat = document.getElementById("edit_tempat");
    const edit_disposisi = document.getElementById("edit_disposisi");
    const edit_keterangan = document.getElementById("edit_keterangan");

    /* ================= HELPER ================= */
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }



    function updatePaginationButtons() {
        if (prevBtn) prevBtn.disabled = currentPage <= 1;
        if (nextBtn) nextBtn.disabled = currentPage >= lastPage;
    }

    function performSearch() {
        const term = searchTerm.toLowerCase().trim();
        tbody.querySelectorAll("tr[data-search]").forEach(row => {
            row.style.display =
                row.getAttribute("data-search").includes(term) ? "" : "none";
        });
    }


    function getTanggalClass(tanggalStr) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const target = new Date(tanggalStr);
        target.setHours(0, 0, 0, 0);

        const diff = (target - today) / 86400000;

        if (diff === 0) return 'agenda-today';
        if (diff === 1) return 'agenda-tomorrow';
        if (diff >= 2) return 'agenda-other';
        return 'agenda-past';
    }

    /* ================= LOAD DATA ================= */
    async function loadKegiatan(page = 1) {
        if (isLoading) return;
        isLoading = true;

        try {
            const res = await fetch(`/kegiatan/list?page=${page}`)
            const result = await res.json();

            currentPage = result.current_page || 1;
            lastPage = result.last_page || 1;
            sessionStorage.setItem("agenda_page_admin", currentPage);

            if (totalBadge) {
                totalBadge.textContent = `${result.total || 0} Data`;
            }

            tbody.innerHTML = "";

            (result.data || []).forEach(k => {
                const row = document.createElement("tr");
                row.className = getTanggalClass(k.tanggal_kegiatan);
                row.setAttribute("data-search",
                    `${k.tanggal_label} ${k.nama_kegiatan} ${k.tempat || ''} ${k.disposisi || ''} ${k.keterangan || ''}`.toLowerCase()
                );

                row.innerHTML = `
                    <td>
                        <div>${k.tanggal_label}</div>
                        ${k.jam ? `<div class="small">${k.jam} WIB</div>` : ""}
                    </td>
                    <td>${k.nama_kegiatan}</td>
                    <td>${k.tempat || "-"}</td>
                    <td>${k.disposisi || "-"}</td>
                    <td>${k.keterangan || "-"}</td>
                    <td class="td-aksi">
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${k.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${k.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            updatePaginationButtons();
            if (searchTerm) performSearch();

        } catch (e) {
            console.error(e);
        } finally {
            isLoading = false;
        }
    }

    /* ================= REALTIME REVERB ================= */
    if (window.Echo) {
        window.Echo.channel('agenda-updates')
            .listen('.AgendaUpdated', async (e) => {

                if (window.__lastEditedId === e.id) {
                    window.__lastEditedId = null;
                    return;
                }

                loadKegiatan(currentPage);
            });
    }

    /* ================= SEARCH ================= */
    if (searchInput) {
        searchInput.addEventListener("input", e => {
            searchTerm = e.target.value;
            performSearch();
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener("click", () => {
            searchInput.value = "";
            searchTerm = "";
            performSearch();
        });
    }

    /* ================= PAGINATION ================= */
    if (prevBtn) prevBtn.onclick = () => loadKegiatan(currentPage - 1);
    if (nextBtn) nextBtn.onclick = () => loadKegiatan(currentPage + 1);

    /* ================= CRUD ================= */
    if (formTambah) {
        formTambah.addEventListener("submit", async function (e) {
            e.preventDefault();

            const res = await fetch("/admin/kegiatan/store", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": getCsrfToken() },
                body: new FormData(this)
            });

            const result = await res.json();

            // ⬇️ TAMBAH INI
            window.__lastEditedId = result.id;

            bootstrap.Modal.getInstance(modalTambahAgenda)?.hide();
            this.reset();
            loadKegiatan(currentPage);
        });
    }

    if (formEdit) {
        formEdit.addEventListener("submit", async function (e) {
            e.preventDefault();

            const id = edit_id.value;

            // ⬇️ TAMBAH INI
            window.__lastEditedId = id;

            await fetch(`/admin/kegiatan/${id}`, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": getCsrfToken() },
                body: new FormData(this)
            });

            bootstrap.Modal.getInstance(modalEditAgenda)?.hide();
            loadKegiatan(currentPage);
        });

    }

    tbody.addEventListener("click", async function (e) {

        const editBtn = e.target.closest(".edit-btn");
        const deleteBtn = e.target.closest(".delete-btn");

        if (editBtn) {
            const id = editBtn.dataset.id;
            const res = await fetch(`/admin/kegiatan/${id}`);
            const k = await res.json();

            edit_id.value = k.kegiatan_id;
            edit_tanggal.value = k.tanggal_kegiatan;
            edit_jam.value = k.jam || "";
            edit_nama.value = k.nama_kegiatan;
            edit_tempat.value = k.tempat || "";
            edit_disposisi.value = k.disposisi || "";
            edit_keterangan.value = k.keterangan || "";

            new bootstrap.Modal(modalEditAgenda).show();
        }

        if (deleteBtn) {
            const id = deleteBtn.dataset.id;

            if (!confirm("Yakin hapus?")) return;

            await fetch(`/admin/kegiatan/${id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": getCsrfToken() }
            });

            loadKegiatan(currentPage);
        }
    });

    /* ================= INIT ================= */
    const savedPage = sessionStorage.getItem("agenda_page_admin") || 1;
    loadKegiatan(savedPage);

});

/* AUTO LOGOUT ADMIN */
const AUTO_LOGOUT_INTERVAL = 3 * 60 * 1000;
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

/* BLUR ACTIVE ELEMENT ON MODAL HIDE */
document.addEventListener('hidden.bs.modal', function () {
    if (document.activeElement) {
        document.activeElement.blur();
    }
});
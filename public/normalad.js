/* =================================
   NORMAL ADMIN JS - REAL-TIME AJAX
   - Real-time data loading
   - Warna baris penuh (hijau/kuning/abu)
   - Sorting: Hari ini → Besok → Setelah besok
   - Search & Pagination
   - Auto logout
================================= */

document.addEventListener("DOMContentLoaded", function() {
    
    const tbody = document.getElementById("agendaTbody");
    const searchInput = document.getElementById("agendaSearch");
    const clearSearchBtn = document.getElementById("agendaClearSearch");
    const prevBtn = document.getElementById("agendaPrevBtn");
    const nextBtn = document.getElementById("agendaNextBtn");
    const totalBadge = document.getElementById("agendaTotalBadge");

    // Form elements
    const formTambah = document.getElementById("form-tambah-agenda");
    const formEdit = document.getElementById("form-edit-agenda");
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

    /* ===== WARNA BARIS PENUH BERDASARKAN STATUS ===== */
    function getRowClass(status) {
        if (status === 'today') return 'agenda-today';
        if (status === 'tomorrow') return 'agenda-tomorrow';
        return 'agenda-other';
    }

    /* ===== LOAD DATA AGENDA ===== */
    async function loadKegiatan(page = 1) {
        currentPage = page;

        try {
            const res = await fetch(`/admin/kegiatan/list?page=${page}`);
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
                row.className = getRowClass(k.status);
                
                // Tambahkan class warna baris penuh
                row.className = getRowClass(k.status);
                
                row.setAttribute("data-search", 
                    `${k.tanggal_label} ${k.nama_kegiatan} ${k.tempat || ''} ${k.disposisi || ''} ${k.keterangan || ''}`.toLowerCase()
                );
                
                row.innerHTML = `
                    <td data-label="Tanggal">
                        <div>${k.tanggal_label}</div>
                        ${k.jam ? `<div class="small">${k.jam} WIB</div>` : ""}
                    </td>
                    <td data-label="Kegiatan">${k.nama_kegiatan}</td>
                    <td data-label="Tempat">${k.tempat || "-"}</td>
                    <td data-label="Disposisi">${k.disposisi || "-"}</td>
                    <td data-label="Keterangan">${k.keterangan || "-"}</td>
                    <td data-label="Aksi" class="td-aksi">
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
        searchInput.addEventListener("input", function() {
            searchTerm = this.value;
            performSearch();
        });
    }

    /* ===== EVENT: CLEAR SEARCH ===== */
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener("click", function() {
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
        formTambah.addEventListener("submit", async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const res = await fetch("/admin/kegiatan/store", {
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
                loadKegiatan(currentPage);

                alert("Agenda berhasil ditambahkan!");

            } catch (error) {
                console.error("Error:", error);
                alert(error.message || "Terjadi kesalahan saat menambahkan agenda");
            }
        });
    }

    /* ===== CLICK EDIT & DELETE BUTTONS ===== */
    if (tbody) {
        tbody.addEventListener("click", async function(e) {

            // HANDLE EDIT
            const editBtn = e.target.closest(".edit-btn");
            if (editBtn) {
                const id = editBtn.dataset.id;

                try {
                    const res = await fetch(`/admin/kegiatan/${id}`);
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
                    const res = await fetch(`/admin/kegiatan/${id}`, {
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
        formEdit.addEventListener("submit", async function(e) {
            e.preventDefault();

            const id = edit_id.value;
            const formData = new FormData(this);

            try {
                const res = await fetch(`/admin/kegiatan/${id}`, {
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
                currentPage = 1;
                loadKegiatan(1);


                alert("Agenda berhasil diperbarui!");

            } catch (error) {
                console.error("Error:", error);
                alert(error.message || "Gagal mengupdate agenda");
            }
        });
    }

    /* ===== REMEMBER COLLAPSE ===== */
    const STORAGE_KEY = "open-section";
    const agendaCollapse = document.getElementById("agendaTableBody");

    if (agendaCollapse) {
        // RESTORE
        if (localStorage.getItem(STORAGE_KEY) === "agendaTableBody") {
            bootstrap.Collapse.getOrCreateInstance(agendaCollapse, {
                toggle: false
            }).show();
        }

        // SAVE
        agendaCollapse.addEventListener("show.bs.collapse", () => {
            localStorage.setItem(STORAGE_KEY, "agendaTableBody");
        });

        agendaCollapse.addEventListener("hidden.bs.collapse", () => {
            localStorage.removeItem(STORAGE_KEY);
        });
    }

    /* ===== MAXLENGTH COUNTER ===== */
    document.querySelectorAll("input[maxlength], textarea[maxlength]").forEach(input => {
        const max = parseInt(input.getAttribute("maxlength"));
        if (!max) return;

        const counter = document.createElement("small");
        counter.className = "text-muted d-block counter-tight";
        input.after(counter);

        const update = () => {
            if (input.value.length > max) {
                input.value = input.value.slice(0, max);
            }
            counter.innerText = `${input.value.length}/${max} karakter`;
        };

        input.addEventListener("input", update);
        update();
    });

    /* ===== AUTO LOGOUT ===== */
    const AUTO_LOGOUT_INTERVAL = 1 * 60 * 1000;
    let autoLogoutTimer;

    function resetAutoLogoutTimer() {
        const form = document.getElementById("auto-logout-form");
        if (!form) return;

        clearTimeout(autoLogoutTimer);
        autoLogoutTimer = setTimeout(() => form.submit(), AUTO_LOGOUT_INTERVAL);
    }

    ["mousemove", "keydown", "click", "scroll", "touchstart"]
        .forEach(e => document.addEventListener(e, resetAutoLogoutTimer, true));

    resetAutoLogoutTimer();

    /* ===== INITIALIZE ===== */
    loadKegiatan(1);

});
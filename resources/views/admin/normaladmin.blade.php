<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - SIGAP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin.css') }}" />
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-dark fixed-top custom-navbar">
    <div class="container-fluid d-flex align-items-center px-3 py-2 position-relative">

        <a class="navbar-brand fw-bold" href="#">
            <i class="fas fa-user-shield"></i>
            <span class="brand-text">Admin Panel</span>
        </a>

        <a href="#"
           class="btn-logout ms-auto"
           data-bs-toggle="modal"
           data-bs-target="#logoutConfirmModal">
            <i class="fas fa-sign-out-alt me-2"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-content pt-5 mt-4">

    <!-- ================= AGENDA ================= -->
    <section id="agenda" class="mb-5">

        <div class="card modern-card">

            <div class="card-header bg-primary text-light modern-card-header"
                 role="button"
                 data-bs-toggle="collapse"
                 data-bs-target="#agendaTableBody"
                 aria-expanded="true"
                 style="cursor:pointer;">

                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i> Agenda Kegiatan
                    </h4>
                    <span class="badge badge-light">{{ count($kegiatan) }} Data</span>
                </div>

            </div>

            <div id="agendaTableBody" class="collapse show">
                <div class="card-body">

                    <!-- SEARCH + ADD -->
                    <div class="mb-3 d-flex gap-2 align-items-center">

                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   id="agendaSearch"
                                   class="form-control"
                                   placeholder="Cari agenda...">
                            <button class="btn btn-outline-secondary" type="button" id="agendaClearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <button class="btn btn-success"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#modalTambahAgenda">
                            <i class="fas fa-plus me-1"></i> Tambah Agenda
                        </button>

                    </div>

                    <!-- TABLE -->
                    <div class="admin-table-wrapper-table agenda-wrapper">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="sticky-thead-admin">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kegiatan</th>
                                    <th>Tempat</th>
                                    <th>Disposisi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="agendaTbody">
                                @foreach ($kegiatan as $k)
                                @php
                                    $eventDate = \Carbon\Carbon::parse($k->tanggal_kegiatan, 'Asia/Jakarta');
                                    $dateFormatted = $eventDate->translatedFormat('l, d F Y');
                                    $jam = $k->jam ? \Carbon\Carbon::parse($k->jam)->format('H.i') : null;
                                @endphp

                                <tr data-search="{{ strtolower($dateFormatted . ' ' . $k->nama_kegiatan . ' ' . $k->disposisi . ' ' . ($k->keterangan ?? '') . ' ' . $k->tempat) }}">
                                    <td>{{ $dateFormatted }} @if($jam) | {{ $jam }} WIB @endif</td>
                                    <td>{{ $k->nama_kegiatan }}</td>
                                    <td>{{ $k->tempat }}</td>
                                    <td>{{ $k->disposisi }}</td>
                                    <td class="td-long-text">{{ $k->keterangan }}</td>
                                    <td>
                                        <div class="aksi-group">
                                            <button type="button"
                                                    class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditKegiatan-{{ $k->kegiatan_id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('superadmin.kegiatan.delete', $k->kegiatan_id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus agenda ini?')"
                                                  class="m-0 d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- NAV -->
                    <div class="agenda-nav-wrapper">
                        <button class="btn-nav-admin" id="agendaPrevBtn" type="button">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn-nav-admin" id="agendaNextBtn" type="button">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div> <!-- END main-content -->


<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="modalTambahAgenda" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i> Tambah Agenda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('superadmin.kegiatan.store') }}" method="POST">
                @csrf

                <div class="modal-body row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal_kegiatan" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jam</label>
                        <input type="time" name="jam" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" maxlength="50" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tempat</label>
                        <input type="text" name="tempat" class="form-control" maxlength="50">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Disposisi</label>
                        <input type="text" name="disposisi" class="form-control" maxlength="20">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" maxlength="50"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ================= MODAL EDIT ================= -->
@foreach ($kegiatan as $k)
<div class="modal fade" id="modalEditKegiatan-{{ $k->kegiatan_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Agenda</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('superadmin.kegiatan.update', $k->kegiatan_id) }}" method="POST">
                @csrf

                <div class="modal-body">

                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_kegiatan" class="form-control"
                           value="{{ \Carbon\Carbon::parse($k->tanggal_kegiatan)->format('Y-m-d') }}">

                    <label class="form-label mt-2">Jam</label>
                    <input type="time" name="jam" class="form-control"
                           value="{{ $k->jam ? \Carbon\Carbon::parse($k->jam)->format('H:i') : '' }}">

                    <label class="form-label mt-2">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-control" value="{{ $k->nama_kegiatan }}">

                    <label class="form-label mt-2">Tempat</label>
                    <input type="text" name="tempat" class="form-control" value="{{ $k->tempat }}">

                    <label class="form-label mt-2">Disposisi</label>
                    <input type="text" name="disposisi" class="form-control" value="{{ $k->disposisi }}">

                    <label class="form-label mt-2">Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ $k->keterangan }}</textarea>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach

<script>
document.addEventListener("DOMContentLoaded", () => {

    const tableName = "agenda";
    const rowsPerPage = 4;

    const tbody = document.getElementById("agendaTbody");
    const searchInput = document.getElementById("agendaSearch");
    const prevBtn = document.getElementById("agendaPrevBtn");
    const nextBtn = document.getElementById("agendaNextBtn");

    if (!tbody) return;

    const allRows = Array.from(tbody.querySelectorAll("tr"));

    let filteredRows = [...allRows];
    let currentPage = 0;

    // ===== RESTORE PAGE AFTER EDIT =====
    const savedPage = sessionStorage.getItem("agenda_lastPage");
    if (savedPage !== null) {
        currentPage = parseInt(savedPage);
        sessionStorage.removeItem("agenda_lastPage");
    }

    function render() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

        if (currentPage < 0) currentPage = 0;
        if (currentPage >= totalPages) currentPage = totalPages - 1;

        allRows.forEach(r => r.style.display = "none");

        const start = currentPage * rowsPerPage;
        const end = start + rowsPerPage;

        const visible = filteredRows.slice(start, end);

        visible.forEach((row, i) => {
            row.style.display = "table-row";

            // ===== COUNTER =====
            const numberCell = row.querySelector(".row-number");
            if (numberCell) {
                numberCell.textContent = start + i + 1;
            }
        });

        if (prevBtn) prevBtn.disabled = currentPage === 0;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages - 1;
    }

    function search() {
        const term = searchInput.value.toLowerCase().trim();

        filteredRows = term === ""
            ? [...allRows]
            : allRows.filter(row =>
                (row.getAttribute("data-search") || "").includes(term)
              );

        currentPage = 0;
        render();
    }

    if (searchInput) {
        searchInput.addEventListener("input", search);
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            currentPage--;
            render();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            currentPage++;
            render();
        });
    }

    // ===== SAVE PAGE BEFORE SUBMIT (EDIT / DELETE) =====
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            sessionStorage.setItem("agenda_lastPage", currentPage);
        });
    });

    render();
});
</script>


</body>
</html>

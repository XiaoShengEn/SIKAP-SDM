<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normal Admin Dashboard - SIGAP</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark fixed-top custom-navbar">
        <div class="container-fluid d-flex align-items-center px-3 py-2 position-relative">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-user-circle me-2"></i>
                <span class="brand-text">Normal Admin Panel</span>
            </a>

            <a href="{{ url('/logout') }}" class="btn-logout ms-auto">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="content">
        <div class="content-wrapper">
            <div class="container-fluid py-4 px-4">

                <!-- ========================= AGENDA ========================= -->
                <section id="agenda" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    <i class="fas fa-calendar-check me-2"></i> Agenda Kegiatan
                                </h4>
                                <span class="badge badge-light">{{ count($kegiatan) }} Agenda</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- SEARCH BOX -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="agendaSearch" class="form-control" placeholder="Cari agenda (tanggal, kegiatan, disposisi, tempat...)">
                                    <button class="btn btn-outline-secondary" type="button" id="agendaClearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TABLE WRAPPER (UNIVERSAL) -->
                            <div class="admin-table-wrapper mb-3" id="agendaTableContainer">
                                <table class="table table-hover mb-0">
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
                                        $eventDate = Carbon\Carbon::parse($k->tanggal_kegiatan, 'Asia/Jakarta');
                                        $dateFormatted = \Carbon\Carbon::parse($k->tanggal_kegiatan)->translatedFormat('l, d F Y');

                                        if ($eventDate->isToday()) {
                                        $statusClass = 'agenda-today';
                                        } elseif ($eventDate->isTomorrow()) {
                                        $statusClass = 'agenda-tomorrow';
                                        } else {
                                        $statusClass = 'agenda-other';
                                        }
                                        @endphp

                                        <tr class="{{ $statusClass }}"
                                            data-search="{{ strtolower($dateFormatted . ' ' . $k->nama_kegiatan . ' ' . $k->disposisi . ' ' . ($k->keterangan ?? '') . ' ' . $k->tempat) }}">
                                            <td data-label="Tanggal">{{ $dateFormatted }}</td>
                                            <td data-label="Kegiatan">{{ $k->nama_kegiatan }}</td>
                                            <td data-label="Tempat">{{ $k->tempat }}</td>
                                            <td data-label="Disposisi">{{ $k->disposisi }}</td>
                                            <td data-label="Keterangan">{{ $k->keterangan }}</td>

                                            <td data-label="Aksi" class="td-aksi">
                                                <div class="aksi-group">
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditKegiatan-{{ $k->kegiatan_id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <form action="{{ route('admin.kegiatan.delete', $k->kegiatan_id) }}"
                                                        method="POST" onsubmit="return confirm('Hapus agenda ini?')" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                        @if(count($kegiatan) === 0)
                                        <tr>
                                            <td colspan="6" class="text-center py-4">Belum ada agenda</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- NAVIGATION BUTTONS -->
                            <div class="d-flex justify-content-center gap-2 mb-4">
                                <button class="btn-nav-admin btn-nav-prev-admin" id="agendaPrevBtn" type="button">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="btn-nav-admin btn-nav-next-admin" id="agendaNextBtn" type="button">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <hr>

                            <h5 class="fw-bold">Tambah Agenda Baru</h5>

                            <form action="{{ route('admin.kegiatan.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal:</label>
                                        <input type="date" name="tanggal_kegiatan" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Kegiatan:</label>
                                        <input type="text" name="nama_kegiatan" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tempat:</label>
                                        <input type="text" name="tempat" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Disposisi:</label>
                                        <input type="text" name="disposisi" class="form-control">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Keterangan:</label>
                                        <textarea name="keterangan" class="form-control"></textarea>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-success w-100" type="submit">
                                            <i class="fas fa-save me-2"></i> Simpan Agenda
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </section>

                <!-- ========================= MODAL EDIT ========================= -->
                @foreach ($kegiatan as $k)
                <div class="modal fade" id="modalEditKegiatan-{{ $k->kegiatan_id }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Agenda</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('admin.kegiatan.update', $k->kegiatan_id) }}" method="POST">
                                @csrf

                                <div class="modal-body">
                                    <label class="form-label">Tanggal:</label>
                                    <input type="date" name="tanggal_kegiatan" class="form-control mb-2" value="{{ $k->tanggal_kegiatan }}">

                                    <label class="form-label">Nama Kegiatan:</label>
                                    <input type="text" name="nama_kegiatan" class="form-control mb-2" value="{{ $k->nama_kegiatan }}">

                                    <label class="form-label">Tempat:</label>
                                    <input type="text" name="tempat" class="form-control mb-2" value="{{ $k->tempat }}">

                                    <label class="form-label">Disposisi:</label>
                                    <input type="text" name="disposisi" class="form-control mb-2" value="{{ $k->disposisi }}">

                                    <label class="form-label">Keterangan:</label>
                                    <textarea name="keterangan" class="form-control mb-2">{{ $k->keterangan }}</textarea>
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

            </div>
        </div>
    </main>

    <!-- JS -->
    <script>
        function initTablePagination(tableName) {
            const tbody = document.getElementById(tableName + "Tbody");
            const searchInput = document.getElementById(tableName + "Search");
            const prevBtn = document.getElementById(tableName + "PrevBtn");
            const nextBtn = document.getElementById(tableName + "NextBtn");

            if (!tbody) return;

            const allRows = Array.from(tbody.querySelectorAll("tr"));
            const dataRows = allRows.filter(row => {
                const cell = row.querySelector("td");
                return cell && !cell.hasAttribute("colspan");
            });

            let filteredRows = [...dataRows];
            let currentPage = 0;
            const rowsPerPage = 4;

            const emptyRowClass = tableName + "-empty-row";
            const firstDataRow = dataRows.find(r => r.querySelectorAll("td").length > 0);
            const colCount = firstDataRow ? firstDataRow.querySelectorAll("td").length : 6;

            let emptyRows = tbody.querySelectorAll("tr." + emptyRowClass);

            if (emptyRows.length < rowsPerPage) {
                for (let i = emptyRows.length; i < rowsPerPage; i++) {
                    const tr = document.createElement("tr");
                    tr.className = emptyRowClass;
                    tr.innerHTML = `<td colspan="${colCount}" style="height:60px;border:none;"></td>`;
                    tbody.appendChild(tr);
                }
            }

            emptyRows = Array.from(tbody.querySelectorAll("tr." + emptyRowClass));

            function showPage(page) {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (totalPages === 0) currentPage = 0;
                else currentPage = Math.max(0, Math.min(page, totalPages - 1));

                dataRows.forEach(r => (r.style.display = 'none'));
                emptyRows.forEach(r => (r.style.display = 'none'));

                const start = currentPage * rowsPerPage;
                const pageRows = filteredRows.slice(start, start + rowsPerPage);

                pageRows.forEach(r => (r.style.display = 'table-row'));

                emptyRows
                    .slice(0, rowsPerPage - pageRows.length)
                    .forEach(r => (r.style.display = 'table-row'));

                if (prevBtn && nextBtn) {
                    prevBtn.disabled = currentPage === 0;
                    nextBtn.disabled = (totalPages === 0) || (currentPage >= totalPages - 1);

                    prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
                    nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
                }
            }

            function performSearch() {
                const keyword = searchInput?.value.toLowerCase().trim() || '';
                filteredRows = keyword ?
                    dataRows.filter(row => (row.dataset.search || '').includes(keyword)) : [...dataRows];

                showPage(0);
            }

            if (searchInput) searchInput.addEventListener('input', performSearch);
            if (prevBtn) prevBtn.addEventListener('click', () => showPage(currentPage - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => showPage(currentPage + 1));

            showPage(0);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initTablePagination('agenda');

            const clearBtn = document.getElementById('agendaClearSearch');
            const searchInput = document.getElementById('agendaSearch');

            if (clearBtn && searchInput) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    initTablePagination('agenda');
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
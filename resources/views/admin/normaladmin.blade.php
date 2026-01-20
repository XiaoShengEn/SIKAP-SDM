<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIKAP SDM</title>

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
                <span class="brand-text">Admin Panel</span>
            </a>

            <a href="#"
                class="btn-logout ms-auto"
                data-bs-toggle="modal"
                data-bs-target="#logoutConfirmModal">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Keluar</span>
            </a>
        </div>
    </nav>

    <!-- CONTENT -->
    <main id="content" class="content">
        <div class="content-wrapper">
            <div class="container-fluid py-4 px-4">

                <!-- ========================= AGENDA ========================= -->
                <section id="agenda" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-light modern-card-header"
                            role="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#agendaTableBody"
                            aria-expanded="true"
                            style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Agenda</h4>
                                <span class="badge badge-light">{{ count($kegiatan) }} Data</span>
                            </div>
                        </div>

                        <div id="agendaTableBody" class="collapse">
                            <div class="card-body">
                                <!-- SEARCH BOX -->
                                <div class="mb-3 d-flex gap-2 align-items-center">

                                    <!-- SEARCH -->
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text"
                                            id="agendaSearch"
                                            class="form-control"
                                            placeholder="Cari agenda (tanggal, kegiatan, disposisi, tempat...)">
                                        <button class="btn btn-outline-secondary" type="button" id="agendaClearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- TOMBOL TAMBAH -->
                                    <button class="btn btn-success"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTambahAgenda">
                                        <i class="fas fa-plus me-1"></i> Tambah Agenda
                                    </button>

                                </div>

                                <!-- TABLE WRAPPER (UNIVERSAL) -->
                                <div class="admin-table-wrapper-table agenda-wrapper">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="sticky-thead-admin">
                                            <tr>
                                                <th class="col-tanggal">Tanggal</th>
                                                <th class="col-kegiatan">Kegiatan</th>
                                                <th class="col-tempat">Tempat</th>
                                                <th class="col-disposisi">Disposisi</th>
                                                <th class="col-keterangan">Keterangan</th>
                                                <th class="col-aksi">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody id="agendaTbody">
                                            @foreach ($kegiatan as $k)
                                            @php
                                            $eventDate = \Carbon\Carbon::parse($k->tanggal_kegiatan, 'Asia/Jakarta');
                                            $dateFormatted = $eventDate->translatedFormat('l, d F Y');

                                            // parsing jam
                                            $jam = $k->jam
                                            ? \Carbon\Carbon::parse($k->jam)->format('H.i')
                                            : null;

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
                                                <td data-label="Tanggal">
                                                    {{ $dateFormatted }}
                                                    @if($jam)
                                                    | {{ $jam }} WIB
                                                    @endif
                                                </td>
                                                <td data-label="Kegiatan">{{ $k->nama_kegiatan }}</td>
                                                <td data-label="Tempat">{{ $k->tempat }}</td>
                                                <td data-label="Disposisi">{{ $k->disposisi }}</td>
                                                <td data-label="Keterangan" class="td-long-text">
                                                    {{ $k->keterangan }}
                                                </td>
                                                <td data-label="Aksi" class="td-aksi">
                                                    <div class="aksi-group">
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditKegiatan-{{ $k->kegiatan_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <form action="{{ route('superadmin.kegiatan.delete', $k->kegiatan_id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')" class="m-0">
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
                                <div class="agenda-nav-wrapper">
                                    <button class="btn-nav-admin btn-nav-prev-admin" id="agendaPrevBtn" type="button">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn-nav-admin btn-nav-next-admin" id="agendaNextBtn" type="button">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                </section>

                <!-- ========================= MODALS ========================= -->

                {{-- Logout --}}
                <div class="modal fade" id="logoutConfirmModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-sign-out-alt me-2"></i> Konfirmasi Keluar
                                </h5>
                                <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body text-center">
                                <p class="fs-5 mb-0">Apakah kamu ingin Keluar?</p>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <!-- 🔥 SATU-SATUNYA LOGOUT -->
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-danger"
                                        onclick="localStorage.removeItem('open-section')">
                                        Ya, Keluar
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- agenda  --}}
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

                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Tanggal</label>
                                            <input type="date"
                                                name="tanggal_kegiatan"
                                                class="form-control"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Jam</label>
                                            <input type="time"
                                                name="jam"
                                                class="form-control"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Nama Kegiatan</label>
                                            <input type="text"
                                                name="nama_kegiatan"
                                                class="form-control"
                                                maxlength="50"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Tempat</label>
                                            <input type="text"
                                                name="tempat"
                                                class="form-control"
                                                maxlength="50">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Disposisi</label>
                                            <input type="text"
                                                name="disposisi"
                                                class="form-control"
                                                maxlength="20">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Keterangan</label>
                                            <textarea name="keterangan"
                                                class="form-control"
                                                rows="3"
                                                maxlength="50"></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-save me-1"></i> Simpan Agenda
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ========================= MODAL EDIT ========================= -->
                @foreach ($kegiatan as $k)
                <div class="modal fade" id="modalEditKegiatan-{{ $k->kegiatan_id }}" data-bs-backdrop="false" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Agenda</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('superadmin.kegiatan.update', $k->kegiatan_id) }}"
                                method="POST"
                                class="agenda-edit-form">
                                @csrf

                                <div class="modal-body">

                                    <label class="form-label">Tanggal:</label>
                                    <input type="date"
                                        name="tanggal_kegiatan"
                                        class="form-control "
                                        value="{{ \Carbon\Carbon::parse($k->tanggal_kegiatan)->format('Y-m-d') }}">

                                    <label class="form-label">Jam:</label>
                                    <input type="time"
                                        name="jam"
                                        class="form-control "
                                        value="{{ $k->jam ? \Carbon\Carbon::parse($k->jam)->format('H:i') : '' }}">

                                    <label class="form-label">Nama Kegiatan:</label>
                                    <input type="text"
                                        name="nama_kegiatan"
                                        class="form-control "
                                        maxlength="50"
                                        value="{{ $k->nama_kegiatan }}">

                                    <label class="form-label">Tempat:</label>
                                    <input type="text"
                                        name="tempat"
                                        class="form-control "
                                        maxlength="50"
                                        value="{{ $k->tempat }}">

                                    <label class="form-label">Disposisi:</label>
                                    <input type="text"
                                        name="disposisi"
                                        class="form-control "
                                        maxlength="20"
                                        value="{{ $k->disposisi }}">

                                    <label class="form-label">Keterangan:</label>
                                    <textarea name="keterangan"
                                        class="form-control "
                                        maxlength="50">{{ $k->keterangan }}</textarea>
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

    <!-- AUTO LOGOUT FORM (JANGAN DIHAPUS) -->
    <form id="auto-logout-form"
        action="{{ route('logout') }}"
        method="POST"
        style="display:none;">
        @csrf
    </form>

    <script src="{{ asset('normalad.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
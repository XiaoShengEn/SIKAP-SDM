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
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg%20width%3D%2250%22%20height%3D%2252%22%20viewBox%3D%220%200%2050%2052%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Ctitle%3ELogomark%3C%2Ftitle%3E%3Cpath%20d%3D%22M49.626%2011.564a.809.809%200%200%201%20.028.209v10.972a.8.8%200%200%201-.402.694l-9.209%205.302V39.25c0%20.286-.152.55-.4.694L20.42%2051.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805%200%200%201-.41%200c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402%2039.944A.801.801%200%200%201%200%2039.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802%200%200%201%20.8%200l9.61%205.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809%200%200%201%20.028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801%200%200%201%20.8%200l9.61%205.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574%2010.718v-9.124l-3.363%201.936-4.646%202.675v9.124l8.01-4.611zm-9.61%2016.505v-9.13l-4.57%202.61-13.05%207.448v9.216l17.62-10.144zM1.602%207.719v31.068L19.22%2048.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965%209.654%201.602%207.72zm8.81-5.994L2.405%206.334l8.005%204.609%208.006-4.61-8.006-4.608zm4.164%2028.764l4.645-2.674V7.719l-3.363%201.936-4.646%202.675v20.096l3.364-1.937zM39.243%207.164l-8.006%204.609%208.006%204.609%208.005-4.61-8.005-4.608zm-.801%2010.605l-4.646-2.675-3.363-1.936v9.124l4.645%202.674%203.364%201.937v-9.124zM20.02%2038.33l11.743-6.704%205.87-3.35-8-4.606-9.211%205.303-8.395%204.833%207.993%204.524z%22%20fill%3D%22%23FF2D20%22%20fill-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                <span class="badge badge-light" id="agendaTotalBadge">0 Data</span>
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
                                            <tr>
                                                <td colspan="6" class="text-center py-4">Memuat data...</td>
                                            </tr>
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

                    <!-- TOMBOL TAMBAH -->
                    <button class="btn btn-success"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambahAgenda">
                        <i class="fas fa-plus me-1"></i> Tambah Agenda
                    </button>

                </div>

                {{-- Tambah Agenda --}}
                <div class="modal fade" id="modalTambahAgenda" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-calendar-plus me-2"></i> Tambah Agenda
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="form-tambah-agenda">
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
                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
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

                {{-- Edit Agenda --}}
                <div class="modal fade" id="modalEditAgenda" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit me-2"></i> Edit Agenda
                                </h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="form-edit-agenda">
                                @csrf

                                <input type="hidden" id="edit_id" name="kegiatan_id">

                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Tanggal</label>
                                            <input type="date"
                                                id="edit_tanggal"
                                                name="tanggal_kegiatan"
                                                class="form-control"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Jam</label>
                                            <input type="time"
                                                id="edit_jam"
                                                name="jam"
                                                class="form-control"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Nama Kegiatan</label>
                                            <input type="text"
                                                id="edit_nama"
                                                name="nama_kegiatan"
                                                class="form-control"
                                                maxlength="50"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Tempat</label>
                                            <input type="text"
                                                id="edit_tempat"
                                                name="tempat"
                                                class="form-control"
                                                maxlength="50">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Disposisi</label>
                                            <input type="text"
                                                id="edit_disposisi"
                                                name="disposisi"
                                                class="form-control"
                                                maxlength="20">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Keterangan</label>
                                            <textarea
                                                id="edit_keterangan"
                                                name="keterangan"
                                                class="form-control"
                                                rows="3"
                                                maxlength="50"></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button class="btn btn-warning" type="submit">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('normalad.js') }}"></script>

    @vite(['resources/js/app.js'])


</body>

</html>

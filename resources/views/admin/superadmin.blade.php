<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin Dashboard - SIKAP SDM</title>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg%20width%3D%2250%22%20height%3D%2252%22%20viewBox%3D%220%200%2050%2052%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Ctitle%3ELogomark%3C%2Ftitle%3E%3Cpath%20d%3D%22M49.626%2011.564a.809.809%200%200%201%20.028.209v10.972a.8.8%200%200%201-.402.694l-9.209%205.302V39.25c0%20.286-.152.55-.4.694L20.42%2051.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805%200%200%201-.41%200c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402%2039.944A.801.801%200%200%201%200%2039.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802%200%200%201%20.8%200l9.61%205.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809%200%200%201%20.028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801%200%200%201%20.8%200l9.61%205.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574%2010.718v-9.124l-3.363%201.936-4.646%202.675v9.124l8.01-4.611zm-9.61%2016.505v-9.13l-4.57%202.61-13.05%207.448v9.216l17.62-10.144zM1.602%207.719v31.068L19.22%2048.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965%209.654%201.602%207.72zm8.81-5.994L2.405%206.334l8.005%204.609%208.006-4.61-8.006-4.608zm4.164%2028.764l4.645-2.674V7.719l-3.363%201.936-4.646%202.675v20.096l3.364-1.937zM39.243%207.164l-8.006%204.609%208.006%204.609%208.005-4.61-8.005-4.608zm-.801%2010.605l-4.646-2.675-3.363-1.936v9.124l4.645%202.674%203.364%201.937v-9.124zM20.02%2038.33l11.743-6.704%205.87-3.35-8-4.606-9.211%205.303-8.395%204.833%207.993%204.524z%22%20fill%3D%22%23FF2D20%22%20fill-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E" />



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

     @vite(['resources/js/app.js'])

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark fixed-top custom-navbar">
        <div class="container-fluid d-flex align-items-center px-3 py-2 position-relative">
            <button class="toggle-btn me-3" id="menuBtn" aria-label="Toggle sidebar" type="button">
                <i class="fas fa-bars-staggered"></i>
            </button>

            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-user-shield"></i>
                <span class="brand-text">Superadmin Panel</span>
            </a>

            <a href="#"
                class="btn-logout ms-auto"
                data-bs-toggle="modal"
                data-bs-target="#logoutConfirmModal">
                <i class="fas fa-sign-out-alt me-2"></i>
                Keluar
            </a>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar" aria-label="Sidebar">
        <nav class="sidebar-nav">
            <a href="#profil" class="sidebar-item">
                <i class="fas fa-user-tie"></i>
                <span class="text">Profil Pimpinan</span>
            </a>

            <a href="#video" class="sidebar-item">
                <i class="fas fa-video-camera"></i>
                <span class="text">Video Kegiatan</span>
            </a>

            <a href="#agenda" class="sidebar-item">
                <i class="fas fa-calendar-check"></i>
                <span class="text">Agenda Kegiatan</span>
            </a>

            <a href="#runningtext" class="sidebar-item">
                <i class="fas fa-bullhorn"></i>
                <span class="text">Teks Berjalan</span>
            </a>

            <a href="#normaladmin" class="sidebar-item">
                <i class="fas fa-users-cog"></i>
                <span class="text">Kelola Admin</span>
            </a>

            <a href="#background" class="sidebar-item">
                <i class="fas fa-image"></i>
                <span class="text">Background TV</span>
            </a>
        </nav>
    </aside>

    <!-- CONTENT -->
    <main id="content" class="content">
        <div class="content-wrapper">
            <div class="container-fluid py-4 px-4">

                <!-- ========================= PROFIL ========================= -->
                <section id="profil" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header"
                            role="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#profilTableBody"
                            aria-expanded="true"
                            style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-user-tie me-2"></i> Profil Pimpinan
                                </h4>
                                <span class="badge badge-light">{{ count($profil) }} Data</span>
                            </div>
                        </div>

                        <div id="profilTableBody" class="collapse">
                            <div class="card-body profil-body">
                                <!-- SEARCH BOX -->
                                <div class="mb-3 d-flex gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" id="profilSearch" class="form-control" placeholder="Cari profil (nama, jabatan...)">
                                        <button class="btn btn-outline-secondary" type="button" id="profilClearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- ✅ TOMBOL TAMBAH -->
                                    <button class="btn btn-success"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTambahProfil">
                                        <i class="fas fa-plus me-1"></i> Tambah Profil
                                    </button>
                                </div>

                                <!-- TABLE WRAPPER (UNIVERSAL) -->
                                <div class="admin-table-wrapper-table ">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="sticky-thead-admin">
                                            <tr>
                                                <th>Foto</th>
                                                <th>Nama</th>
                                                <th>Jabatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody id="profilTbody">
                                            @forelse ($profil as $p)
                                            <tr data-search="{{ strtolower($p->nama_pimpinan . ' ' . $p->jabatan_pimpinan) }}">
                                                <td data-label="Foto">
                                                    <img src="{{ asset('uploads/profil/' . $p->foto_pimpinan) }}#profil" alt="{{ $p->nama_pimpinan }}" class="img-profil-table">
                                                </td>
                                                <td data-label="Nama">{{ $p->nama_pimpinan }}</td>
                                                <td data-label="Jabatan">{{ $p->jabatan_pimpinan }}</td>
                                                <td data-label="Aksi" class="td-aksi">
                                                    <div class="aksi-group">
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditProfil-{{ $p->id_profil }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <form action="{{ url('superadmin/profil/delete/' . $p->id_profil) }}#profil" method="POST" onsubmit="return confirm('Hapus profil ini?')" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">Belum ada data</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- NAVIGATION BUTTONS -->
                                <div class="profil-nav-wrapper">
                                    <button class="btn-nav-admin btn-nav-prev-admin" id="profilPrevBtn" type="button">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn-nav-admin btn-nav-next-admin" id="profilNextBtn" type="button">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                </section>

                <!-- ========================= VIDEO ========================= -->
                <section id="video" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header"
                            role="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#videoTableBody"
                            aria-expanded="true"
                            style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-video me-2"></i> Video Kegiatan
                                </h4>
                                <span class="badge badge-light">{{ count($videos) }} Data</span>
                            </div>
                        </div>

                        <div id="videoTableBody" class="collapse">
                            <div class="card-body">

                                <!-- SEARCH + BUTTON -->
                                <div class="mb-3 d-flex gap-2 align-items-center">

                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>

                                        <input type="text"
                                            id="videoSearch"
                                            class="form-control"
                                            placeholder="Cari video...">


                                        <button class="btn btn-outline-secondary" type="button" id="videoClearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <button class="btn btn-success"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTambahVideo">
                                        <i class="fas fa-plus me-1"></i> Tambah Video
                                    </button>

                                </div>

                                <!-- TABLE WRAPPER -->
                                <div class="admin-table-wrapper-fluid mb-3" id="videoTableContainer">
                                    <table id="videoTable" class="table table-hover align-middle mb-0">
                                        <thead class="sticky-thead-admin">
                                            <tr>
                                                <th>Video</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody id="videoTbody">
                                            @foreach ($videos as $v)
                                            <tr data-search="{{ strtolower($v->video_keterangan ?? '') }}">
                                                <td data-label="Video" class="video-cell">
                                                    <video controls preload="metadata">
                                                        <source src="{{ asset('videos/' . $v->video_kegiatan) }}">
                                                    </video>
                                                </td>

                                                <td data-label="Keterangan" class="align-middle">
                                                    <div class="p-2">
                                                        {{ $v->video_keterangan }}
                                                    </div>
                                                </td>

                                                <td data-label="Aksi" class="td-aksi">
                                                    <div class="aksi-group">
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditVideo-{{ $v->video_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <form action="{{ route('superadmin.video.delete', $v->video_id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Hapus video ini?')"
                                                            class="m-0">
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

                                            @if(count($videos) === 0)
                                            <tr>
                                                <td colspan="3" class="text-center py-4">Belum ada video</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- NAVIGATION BUTTONS -->
                                <div class="d-flex justify-content-center gap-2 mb-4">
                                    <button class="btn-nav-admin btn-nav-prev-admin" id="videoPrevBtn" type="button">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn-nav-admin btn-nav-next-admin" id="videoNextBtn" type="button">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                </section>

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
                                <h4 class="mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i> Agenda Kegiatan
                                </h4>
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

                                        <tbody id="kegiatan-body">
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

                <!-- ========================= RUNNING TEXT ========================= -->
                <section id="runningtext" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header"
                            role="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#runningTextBody"
                            aria-expanded="true"
                            style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="fas fa-scroll me-2"></i> Teks Berjalan</h4>
                                <span class="badge badge-light">{{ count($runningtext) }} Data</span>
                            </div>
                        </div>

                        <div id="runningTextBody" class="collapse">
                            <div class="card-body">
                                <!-- SEARCH BOX -->
                                <div class="mb-3 d-flex gap-2 align-items-center">

                                    <!-- SEARCH -->
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text"
                                            id="runningtextSearch"
                                            class="form-control"
                                            placeholder="Cari teks berjalan...">
                                        <button class="btn btn-outline-secondary" type="button" id="runningtextClearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- TOMBOL TAMBAH -->
                                    <button class="btn btn-success"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTambahRunningText">
                                        <i class="fas fa-plus me-1"></i> Tambah Teks
                                    </button>

                                </div>

                                <!-- TABLE WRAPPER (UNIVERSAL) -->
                                <div class="admin-table-wrapper-table running-wrapper">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Isi Teks Berjalan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody id="runningtextTbody">
                                            @foreach ($runningtext as $r)
                                            <tr data-search="{{ strtolower($r->isi_text ?? '') }}">
                                                <td data-label="Isi">{{ $r->isi_text }}</td>
                                                <td data-label="Aksi" class="td-aksi">
                                                    <div class="aksi-group">
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditRunningText-{{ $r->id_text }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <form action="{{ route('superadmin.runningtext.delete', $r->id_text) }}" method="POST" onsubmit="return confirm('Hapus teks berjalan ini?')" class="m-0">
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

                                            @if(count($runningtext) === 0)
                                            <tr>
                                                <td colspan="2" class="text-center py-4">Belum ada teks berjalan</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- NAVIGATION BUTTONS -->
                                <div class="d-flex justify-content-center gap-3 mt-3 mb-4">
                                    <button class="btn-nav-admin btn-nav-prev-admin" id="runningtextPrevBtn" type="button">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn-nav-admin btn-nav-next-admin" id="runningtextNextBtn" type="button">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                </section>

                <!-- ========================= KELOLA ADMIN ========================= -->
                <section id="normaladmin" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header"
                            role="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#adminTableBody"
                            aria-expanded="true"
                            style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="fas fa-users-cog me-2"></i> Kelola Admin</h4>
                                <span class="badge badge-light">{{ count($normaladmin) }} Data</span>
                            </div>
                        </div>

                        <div id="adminTableBody" class="collapse">
                            <div class="card-body">
                                <!-- SEARCH BOX -->
                                <div class="mb-3 d-flex gap-2 align-items-center">

                                    <!-- SEARCH -->
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text"
                                            id="normaladminSearch"
                                            class="form-control"
                                            placeholder="Cari admin (nama, email...)">
                                        <button class="btn btn-outline-secondary" type="button" id="normaladminClearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- TOMBOL TAMBAH -->
                                    <button class="btn btn-success"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTambahNormalAdmin">
                                        <i class="fas fa-plus me-1"></i> Tambah Admin
                                    </button>

                                </div>

                                <!-- TABLE WRAPPER (UNIVERSAL) -->
                                <div class="admin-table-wrapper-table">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="sticky-thead-admin">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Bagian</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody id="normaladminTbody">
                                            @foreach ($normaladmin as $n)
                                            <tr data-search="{{ strtolower($n->nama_admin . ' ' . $n->email_admin) }}">
                                                <td data-label="Nama">{{ $n->nama_admin }}</td>
                                                <td data-label="Bagian">{{ $n->bagian ?? '-' }}</td>
                                                <td data-label="Aksi" class="td-aksi">
                                                    <div class="aksi-group">
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm btn-edit-admin"
                                                            data-id="{{ $n->id_admin }}"
                                                            data-nip="{{ $n->nip }}"
                                                            data-nama="{{ $n->nama_admin }}"
                                                            data-bagian="{{ $n->bagian }}"
                                                            data-role="{{ $n->role_admin }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button type="button"
                                                            class="btn btn-danger btn-sm btn-delete-admin"
                                                            data-id="{{ $n->id_admin }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach

                                            @if(count($normaladmin) === 0)
                                            <tr>
                                                <td colspan="3" class="text-center py-4">Belum ada admin</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- NAVIGATION BUTTONS -->
                                <div class="d-flex justify-content-center gap-2 mb-4">
                                    <button class="btn-nav-admin btn-nav-prev-admin" id="normaladminPrevBtn" type="button">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn-nav-admin btn-nav-next-admin" id="normaladminNextBtn" type="button">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>


                            </div>
                        </div>
                </section>

                <section id="background" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header"
                            role="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#backgroundBody"
                            aria-expanded="true"
                            style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="fas fa-image me-2"></i> Background TV</h4>
                                <span class="badge badge-light">{{ $backgroundCount }} Data</span>
                            </div>
                        </div>

                        <div id="backgroundBody" class="collapse">
                            <div class="card-body">
                                <div class="row g-4 align-items-start">
                                    <div class="col-lg-7">
                                        <form action="{{ route('superadmin.background.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label class="form-label fw-bold">Upload Gambar Kantor</label>
                                            <input type="file"
                                                name="background_image"
                                                class="form-control"
                                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                                required>
                                            <small class="text-muted d-block mt-2">Rekomendasi landscape 16:9 agar pas untuk layar TV.</small>
                                            <button type="submit" class="btn btn-success mt-3">
                                                <i class="fas fa-plus me-1"></i> Tambah Background
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-lg-5">
                                        <label class="form-label fw-bold d-block">Preview Saat Ini</label>
                                        @if (!empty($backgroundImage))
                                        <img src="{{ $backgroundImage }}" alt="Preview background TV" class="img-fluid rounded border shadow-sm">
                                        @else
                                        <div class="alert alert-secondary mb-0">Belum ada gambar background. Upload dulu untuk dipakai di halaman TV.</div>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="admin-table-wrapper-table">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="sticky-thead-admin">
                                            <tr>
                                                <th>Preview</th>
                                                <th>Nama File</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($backgroundItems as $bg)
                                            <tr>
                                                <td style="width: 180px;">
                                                    <img src="{{ $bg['url'] }}" alt="background {{ $bg['id'] }}" class="img-fluid rounded border" style="max-height:80px; object-fit:cover;">
                                                </td>
                                                <td>{{ $bg['original_name'] }}</td>
                                                <td>
                                                    @if ($bg['is_active'])
                                                    <span class="badge bg-success">Aktif</span>
                                                    @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                    @endif
                                                </td>
                                                <td class="td-aksi">
                                                    <div class="aksi-group">
                                                        @if (!$bg['is_active'])
                                                        <form action="{{ route('superadmin.background.activate', $bg['id']) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-check-circle"></i>
                                                            </button>
                                                        </form>
                                                        @else
                                                        <button type="button" class="btn btn-sm btn-success" disabled>
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                        @endif

                                                        <form action="{{ route('superadmin.background.delete', $bg['id']) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus background ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">Belum ada data background</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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

                {{-- Profil --}}
                <div class="modal fade" id="modalTambahProfil" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-plus me-2"></i> Tambah Profil Pimpinan
                                </h5>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ url('superadmin/profil/store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <label class="form-label fw-bold">Foto:</label>
                                    <input type="file" class="form-control mb-3" name="foto_pimpinan" accept="image/*" required>

                                    <label class="form-label fw-bold">Nama:</label>
                                    <input type="text"
                                        class="form-control "
                                        name="nama_pimpinan"
                                        maxlength="100"
                                        required>

                                    <label class="form-label fw-bold">Jabatan:</label>
                                    <input type="text"
                                        class="form-control "
                                        name="jabatan_pimpinan"
                                        maxlength="100"
                                        required>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-success w-100" type="submit">
                                        <i class="fas fa-save me-2"></i> Simpan Profil
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- Video --}}
                <div class="modal fade" id="modalTambahVideo" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-plus-circle me-2"></i> Tambah Video
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('superadmin.video.store') }}"
                                method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">

                                    <!-- FILE VIDEO -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">File Video</label>
                                        <input type="file"
                                            name="video_kegiatan"
                                            class="form-control"
                                            accept="video/*"
                                            required>
                                    </div>

                                    <!-- KETERANGAN -->
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Keterangan Video</label>
                                        <textarea name="video_keterangan"
                                            id="video_keterangan_modal"
                                            class="form-control"
                                            rows="3"
                                            maxlength="100"
                                            placeholder="Maksimal 100 karakter..."
                                            required></textarea>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                </div>

                            </form>

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

                            <form id="form-kegiatan">
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
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>


                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Running text --}}
                <div class="modal fade" id="modalTambahRunningText" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-bullhorn me-2"></i> Tambah Teks Berjalan
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('superadmin.runningtext.store') }}" method="POST">
                                @csrf

                                <div class="modal-body">

                                    <label class="form-label fw-bold">Isi Teks Berjalan</label>
                                    <textarea name="isi_text"
                                        class="form-control"
                                        rows="3"
                                        maxlength="100"
                                        placeholder="Maksimal 100 karakter..."
                                        required></textarea>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                {{-- tambah admin --}}
                <div class="modal fade" id="modalTambahNormalAdmin" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-plus me-2"></i> Tambah Admin
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="formTambahAdmin">
                                @csrf

                                <div class="modal-body">
                                    <div class="row g-3">

                                        <!-- NIP -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">NIP</label>
                                            <input type="text"
                                                name="nip"
                                                class="form-control"
                                                maxlength="18"
                                                required>
                                        </div>

                                        <!-- Role -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Role</label>
                                            <select name="role_admin" class="form-select" required>
                                                <option value="normaladmin">Admin</option>
                                                <option value="superadmin">Super Admin</option>
                                            </select>
                                        </div>

                                        <!-- Nama -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Nama Admin</label>
                                            <input type="text"
                                                name="nama_admin"
                                                class="form-control"
                                                maxlength="50"
                                                required>
                                        </div>

                                        <!-- Bagian -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Bagian</label>
                                            <input type="text"
                                                name="bagian"
                                                class="form-control"
                                                maxlength="50"
                                                required>
                                        </div>

                                        <!-- Password -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Password</label>
                                            <div class="input-group w-100">
                                                <input type="password"
                                                    name="password_admin"
                                                    id="password_admin_modal"
                                                    class="form-control"
                                                    minlength="8"
                                                    maxlength="20"
                                                    required>

                                                <button type="button"
                                                    class="btn btn-outline-secondary password-toggle"
                                                    data-target="password_admin_modal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Konfirmasi Password -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Konfirmasi Password</label>
                                            <div class="input-group w-100">
                                                <input type="password"
                                                    name="password_admin_confirmation"
                                                    id="password_admin_confirm_modal"
                                                    class="form-control"
                                                    minlength="8"
                                                    maxlength="20"
                                                    required>

                                                <button type="button"
                                                    class="btn btn-outline-secondary password-toggle"
                                                    data-target="password_admin_confirm_modal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        Simpan Admin
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                @foreach ($profil as $p)
                <div class="modal fade" id="modalEditProfil-{{ $p->id_profil }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Profil</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ url('superadmin/profil/update/' . $p->id_profil) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-bold">Foto Lama:</label><br>
                                    <img src="{{ asset('uploads/profil/' . $p->foto_pimpinan) }}" width="120" height="120" class="rounded mb-3">

                                    <label class="form-label fw-bold">Ganti Foto:</label>
                                    <input type="file" name="foto_pimpinan" class="form-control mb-3" accept="image/*">

                                    <label class="form-label fw-bold">Nama:</label>
                                    <input type="text"
                                        name="nama_pimpinan"
                                        value="{{ $p->nama_pimpinan }}"
                                        class="form-control "
                                        maxlength="100"
                                        required>

                                    <label class="form-label fw-bold">Jabatan:</label>
                                    <input type="text"
                                        name="jabatan_pimpinan"
                                        value="{{ $p->jabatan_pimpinan }}"
                                        class="form-control "
                                        maxlength="100"
                                        required>
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

                @foreach ($videos as $v)
                <div class="modal fade" id="modalEditVideo-{{ $v->video_id }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Video</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('superadmin.video.update', $v->video_id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-bold">Video Lama:</label>
                                    <video controls class="mb-3" style="max-width: 100%;">
                                        <source src="{{ asset('videos/' . $v->video_kegiatan) }}">
                                    </video>

                                    <label class="form-label fw-bold">Ganti Video:</label>
                                    <input type="file" name="video_kegiatan" class="form-control mb-3" accept="video/*">

                                    <label class="form-label fw-bold">Keterangan Video:</label>
                                    <textarea name="video_keterangan"
                                        class="form-control "
                                        rows="3"
                                        maxlength="100"
                                        required>{{ $v->video_keterangan }}</textarea>

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

                @foreach ($videos as $v)
                <div class="modal fade" id="modalEditVideo-{{ $v->video_id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit me-2"></i> Edit Video
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('superadmin.video.update', $v->video_id) }}"
                                method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">

                                    <!-- PREVIEW -->
                                    <div class="mb-3 text-center">
                                        <video controls width="100%" style="max-height:250px;">
                                            <source src="{{ asset('videos/' . $v->video_kegiatan) }}">
                                        </video>
                                    </div>

                                    <!-- GANTI VIDEO -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ganti Video (Opsional)</label>
                                        <input type="file"
                                            name="video_kegiatan"
                                            class="form-control"
                                            accept="video/*">
                                    </div>

                                    <!-- KETERANGAN -->
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Keterangan</label>
                                        <textarea name="video_keterangan"
                                            class="form-control video-edit-textarea"
                                            rows="3"
                                            maxlength="100"
                                            data-counter="counter-{{ $v->video_id }}"
                                            required>{{ $v->video_keterangan }}</textarea>

                                        <small class="text-muted">
                                            <span id="counter-{{ $v->video_id }}">
                                                {{ strlen($v->video_keterangan) }}
                                            </span> / 100 karakter
                                        </small>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Tutup
                                    </button>
                                    <button class="btn btn-warning" type="submit">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Modal Edit Agenda -->
                <div class="modal fade" id="modalEditAgenda" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit me-2"></i> Edit Agenda
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="form-edit-kegiatan">
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
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                @foreach ($runningtext as $r)
                <div class="modal fade" id="modalEditRunningText-{{ $r->id_text }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Teks Berjalan</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('superadmin.runningtext.update', $r->id_text) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-bold">Isi Teks Berjalan:</label>
                                    <textarea name="isi_text" class="form-control " rows="3" maxlength="100" required>{{ $r->isi_text }}</textarea>
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

                <div class="modal fade" id="modalEditNormalAdmin" data-bs-backdrop="false" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i> Edit Admin
                                </h5>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="formEditAdmin">
                                @csrf
                                <input type="hidden" id="edit_id_admin">

                                <div class="modal-body">
                                    <div class="row g-3">
                                        <!-- NIP -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">NIP</label>
                                            <input type="text"
                                                name="nip"
                                                id="edit_nip"
                                                class="form-control"
                                                maxlength="18"
                                                required>
                                        </div>

                                        <!-- Role -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Role</label>
                                            <select name="role_admin" id="edit_role_admin" class="form-select" required>
                                                <option value="normaladmin">Admin</option>
                                                <option value="superadmin">Super Admin</option>
                                            </select>
                                        </div>

                                        <!-- Nama -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Nama Admin</label>
                                            <input type="text"
                                                name="nama_admin"
                                                id="edit_nama_admin"
                                                class="form-control"
                                                maxlength="50"
                                                required>
                                        </div>

                                        <!-- Bagian -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Bagian</label>
                                            <input type="text"
                                                name="bagian"
                                                id="edit_bagian"
                                                class="form-control"
                                                maxlength="50"
                                                required>
                                        </div>

                                        <!-- Password -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Password (opsional)</label>
                                            <div class="input-group w-100">
                                                <input type="password"
                                                    name="password_admin"
                                                    id="edit_password_admin"
                                                    class="form-control"
                                                    minlength="8"
                                                    maxlength="20">

                                                <button type="button"
                                                    class="btn btn-outline-secondary password-toggle"
                                                    data-target="edit_password_admin">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Konfirmasi Password -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Konfirmasi Password</label>
                                            <div class="input-group w-100">
                                                <input type="password"
                                                    name="password_admin_confirmation"
                                                    id="edit_password_admin_confirmation"
                                                    class="form-control"
                                                    minlength="8"
                                                    maxlength="20">

                                                <button type="button"
                                                    class="btn btn-outline-secondary password-toggle"
                                                    data-target="edit_password_admin_confirmation">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        Simpan Admin
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Ganti bagian script di akhir file superadmin.blade.php -->

    <!-- AUTO LOGOUT FORM (JANGAN DIHAPUS) -->
    <form id="auto-logout-form"
        action="{{ route('logout') }}"
        method="POST"
        style="display:none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('superad.js') }}"></script>

</body>

</html>

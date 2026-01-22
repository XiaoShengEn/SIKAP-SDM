<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin Dashboard - SIKAP SDM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin.css') }}" />
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

                                <hr>

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
                                <h4 class="mb-0"><i class="fas fa-scroll me-2"></i> Text Berjalan</h4>
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
                                            placeholder="Cari running text...">
                                        <button class="btn btn-outline-secondary" type="button" id="runningtextClearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- TOMBOL TAMBAH -->
                                    <button class="btn btn-success"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTambahRunningText">
                                        <i class="fas fa-plus me-1"></i> Tambah Text
                                    </button>

                                </div>

                                <!-- TABLE WRAPPER (UNIVERSAL) -->
                                <div class="admin-table-wrapper-table running-wrapper">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Isi Running Text</th>
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

                                                        <form action="{{ route('superadmin.runningtext.delete', $r->id_text) }}" method="POST" onsubmit="return confirm('Hapus running text ini?')" class="m-0">
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
                                                <td colspan="2" class="text-center py-4">Belum ada running text</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- NAVIGATION BUTTONS -->
                                <div class="d-flex justify-content-center gap-2 mb-4">
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
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditNormalAdmin-{{ $n->id_admin }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <form action="{{ route('superadmin.normaladmin.delete', $n->id_admin) }}" method="POST" onsubmit="return confirm('Hapus admin ini?')" class="m-0">
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

                {{-- Running text --}}
                <div class="modal fade" id="modalTambahRunningText" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-bullhorn me-2"></i> Tambah Running Text
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('superadmin.runningtext.store') }}" method="POST">
                                @csrf

                                <div class="modal-body">

                                    <label class="form-label fw-bold">Isi Running Text</label>
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

                            <form action="{{ route('superadmin.normaladmin.store') }}" method="POST">
                                @csrf

                                <div class="modal-body">

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">NIP</label>
                                            <input type="text"
                                                name="nip"
                                                placeholder="Masukkan 18 NIP anda"
                                                class="form-control @error('nip','addAdmin') is-invalid @enderror"
                                                value="{{ old('nip') }}"
                                                maxlength="18"
                                                inputmode="numeric"
                                                pattern="[0-9]{18}"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,18);"
                                                required>

                                            @error('nip','addAdmin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Role</label>
                                            <select name="role_admin" class="form-control" required>
                                                <option value="normaladmin">Admin</option>
                                                <option value="superadmin">Super Admin</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Nama Admin</label>
                                            <input type="text"
                                                name="nama_admin"
                                                class="form-control @error('nama_admin','addAdmin') is-invalid @enderror"
                                                value="{{ old('nama_admin') }}">

                                            @error('nama_admin','addAdmin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Bagian</label>
                                            <input type="text"
                                                name="bagian"
                                                class="form-control"
                                                maxlength="30"
                                                required>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Password</label>

                                            <div class="input-group password-wrapper">
                                                <input type="password"
                                                    name="password_admin"
                                                    id="password_admin_modal"
                                                    class="form-control @error('password_admin','addAdmin') is-invalid @enderror"
                                                    minlength="8"
                                                    maxlength="20"
                                                    required>

                                                <button type="button"
                                                    class="btn btn-outline-secondary password-toggle"
                                                    data-target="password_admin_modal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                            @error('password_admin','addAdmin')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Konfirmasi password</label>

                                            <div class="input-group password-wrapper">
                                                <input type="password"
                                                    name="password_admin_confirmation"
                                                    id="password_admin_confirm_modal"
                                                    class="form-control @error('password_admin','addAdmin') is-invalid @enderror"
                                                    minlength="8"
                                                    maxlength="20"
                                                    required>

                                                <button type="button"
                                                    class="btn btn-outline-secondary password-toggle"
                                                    data-target="password_admin_confirm_modal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                            @error('password_admin','addAdmin')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button class="btn btn-success" type="submit">
                                            <i class="fas fa-save me-1"></i> Simpan Admin
                                        </button>
                                    </div>
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
                                        class="form-control "
                                        maxlength="100"
                                        required>

                                    <label class="form-label fw-bold">Jabatan:</label>
                                    <input type="text"
                                        name="jabatan_pimpinan"
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

                @foreach ($runningtext as $r)
                <div class="modal fade" id="modalEditRunningText-{{ $r->id_text }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Running Text</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('superadmin.runningtext.update', $r->id_text) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-bold">Isi Running Text:</label>
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

                @foreach ($normaladmin as $n)
                <div class="modal fade" id="modalEditNormalAdmin-{{ $n->id_admin }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Admin</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('superadmin.normaladmin.update', $n->id_admin) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-bold">NIP</label>
                                    <input type="text"
                                        name="nip"
                                        placeholder="Masukkan 18 NIP anda"
                                        class="form-control @error('nip', 'editAdmin-'.$n->id_admin) is-invalid @enderror"
                                        value="{{ old('nip', $n->nip) }}"
                                        maxlength="18"
                                        inputmode="numeric"
                                        pattern="[0-9]{18}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,18);"
                                        required>

                                    @error('nip', 'editAdmin-'.$n->id_admin)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label fw-bold">Nama Admin:</label>
                                    <input type="text"
                                        name="nama_admin"
                                        class="form-control @error('nama_admin', " editAdmin-$n->id_admin") is-invalid @enderror"
                                    value="{{ old('nama_admin', $n->nama_admin) }}">

                                    @error('nama_admin', "editAdmin-$n->id_admin")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label fw-bold">Bagian:</label>
                                    <input type="text"
                                        name="bagian"
                                        class="form-control "
                                        maxlength="30"
                                        value="{{ $n->bagian }}"
                                        required>

                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">
                                            Password (Kosongkan jika tidak diganti)
                                        </label>

                                        <div class="input-group password-wrapper">
                                            <input type="password"
                                                name="password_admin"
                                                id="password_edit_{{ $n->id_admin }}"
                                                class="form-control @error('password_admin','editAdmin-'.$n->id_admin) is-invalid @enderror"
                                                minlength="8"
                                                maxlength="20">

                                            <button type="button"
                                                class="btn btn-outline-secondary password-toggle"
                                                data-target="password_edit_{{ $n->id_admin }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>

                                        @error('password_admin','editAdmin-'.$n->id_admin)
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">Konfirmasi password</label>

                                        <div class="input-group password-wrapper">
                                            <input type="password"
                                                name="password_admin_confirmation"
                                                id="password_edit_confirm_{{ $n->id_admin }}"
                                                class="form-control @error('password_admin','editAdmin-'.$n->id_admin) is-invalid @enderror"
                                                minlength="8"
                                                maxlength="20">

                                            <button type="button"
                                                class="btn btn-outline-secondary password-toggle"
                                                data-target="password_edit_confirm_{{ $n->id_admin }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>

                                        @error('password_admin','editAdmin-'.$n->id_admin)
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

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

    <!-- Ganti bagian script di akhir file superadmin.blade.php -->

    <!-- AUTO LOGOUT FORM (JANGAN DIHAPUS) -->
    <form id="auto-logout-form"
        action="{{ route('logout') }}"
        method="POST"
        style="display:none;">
        @csrf
    </form>

    <script src="{{ asset('superad.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script untuk membuka modal tambah admin jika ada error --}}
    @if ($errors->hasBag('addAdmin'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('modalTambahNormalAdmin')).show();
        });
    </script>
    @endif

    {{-- Script untuk membuka modal edit admin jika ada error --}}
    @foreach ($normaladmin as $n)
    @if ($errors->hasBag("editAdmin-$n->id_admin"))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('modalEditNormalAdmin-{{ $n->id_admin }}')).show();
        });
    </script>
    @endif
    @endforeach

</body>

</html>
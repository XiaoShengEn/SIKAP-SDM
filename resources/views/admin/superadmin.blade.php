<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin Dashboard - SIGAP</title>

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

            <a href="{{ url('/logout') }}" class="btn-logout ms-auto">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>
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
                <span class="text">Running Text</span>
            </a>

            <a href="#normaladmin" class="sidebar-item">
                <i class="fas fa-users-cog"></i>
                <span class="text">Kelola Normal Admin</span>
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
                        <div class="card-header bg-primary text-white modern-card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    <i class="fas fa-user-tie me-2"></i> Profil Pimpinan
                                </h4>
                                <span class="badge badge-light">{{ count($profil) }} Data</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- SEARCH BOX -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="profilSearch" class="form-control" placeholder="Cari profil (nama, jabatan...)">
                                    <button class="btn btn-outline-secondary" type="button" id="profilClearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TABLE WRAPPER (UNIVERSAL) -->
                            <div class="admin-table-wrapper mb-3" id="profilTableContainer">
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
                            <div class="d-flex justify-content-center gap-2 mb-4">
                                <button class="btn-nav-admin btn-nav-prev-admin" id="profilPrevBtn" type="button">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="btn-nav-admin btn-nav-next-admin" id="profilNextBtn" type="button">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <hr>

                            <h5 class="fw-bold">Tambah Profil Baru</h5>

                            <form action="{{ url('superadmin/profil/store') }}#profil" method="POST" enctype="multipart/form-data">
                                @csrf

                                <label class="form-label fw-bold">Foto:</label>
                                <input type="file" class="form-control mb-3" name="foto_pimpinan" accept="image/*" required>

                                <label class="form-label fw-bold">Nama:</label>
                                <input type="text" class="form-control mb-3" name="nama_pimpinan" required>

                                <label class="form-label fw-bold">Jabatan:</label>
                                <input type="text" class="form-control mb-3" name="jabatan_pimpinan" required>

                                <button class="btn btn-success w-100" type="submit">
                                    <i class="fas fa-save me-2"></i> Simpan Profil
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- ========================= VIDEO ========================= -->
                <section id="video" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    <i class="fas fa-video-camera me-2"></i> Video Kegiatan
                                </h4>
                                <span class="badge badge-light">{{ count($videos) }} Video</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- SEARCH BOX -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="videoSearch" class="form-control" placeholder="Cari video (keterangan...)">
                                    <button class="btn btn-outline-secondary" type="button" id="videoClearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TABLE WRAPPER (UNIVERSAL) -->
                            <div class="admin-table-wrapper mb-3" id="videoTableContainer">
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
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditVideo-{{ $v->video_id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <form action="{{ route('superadmin.video.delete', $v->video_id) }}" method="POST" onsubmit="return confirm('Hapus video ini?')" class="m-0">
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

                            <h5 class="fw-bold">Upload Video Baru</h5>

                            <form action="{{ route('superadmin.video.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="file" name="video_kegiatan" class="form-control mb-3" accept="video/*" required>

                                <label class="form-label fw-bold">Keterangan Video:</label>
                                <textarea name="video_keterangan" class="form-control mb-3" rows="3" required></textarea>

                                <button class="btn btn-success w-100" type="submit">
                                    <i class="fas fa-upload me-2"></i> Upload Video
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

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

                            <form action="{{ route('superadmin.kegiatan.store') }}" method="POST">
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

                <!-- ========================= RUNNING TEXT ========================= -->
                <section id="runningtext" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    <i class="fas fa-bullhorn me-2"></i> Running Text
                                </h4>
                                <span class="badge badge-light">{{ count($runningtext) }} Text</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- SEARCH BOX -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="runningtextSearch" class="form-control" placeholder="Cari running text...">
                                    <button class="btn btn-outline-secondary" type="button" id="runningtextClearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TABLE WRAPPER (UNIVERSAL) -->
                            <div class="admin-table-wrapper mb-3" id="runningtextTableContainer">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="sticky-thead-admin">
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

                            <hr>

                            <h5 class="fw-bold">Tambah Running Text Baru</h5>

                            <form action="{{ route('superadmin.runningtext.store') }}" method="POST">
                                @csrf
                                <label class="form-label fw-bold">Isi Running Text:</label>
                                <textarea name="isi_text" class="form-control mb-3" rows="3" required></textarea>

                                <button class="btn btn-success w-100" type="submit">
                                    <i class="fas fa-save me-2"></i> Tambah Running Text
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- ========================= KELOLA ADMIN ========================= -->
                <section id="normaladmin" class="mb-5">
                    <div class="card modern-card">
                        <div class="card-header bg-primary text-white modern-card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">
                                    <i class="fas fa-users-cog me-2"></i> Kelola Normal Admin
                                </h4>
                                <span class="badge badge-light">{{ count($normaladmin) }} Admin</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- SEARCH BOX -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="normaladminSearch" class="form-control" placeholder="Cari admin (nama, email...)">
                                    <button class="btn btn-outline-secondary" type="button" id="normaladminClearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TABLE WRAPPER (UNIVERSAL) -->
                            <div class="admin-table-wrapper mb-3" id="normaladminTableContainer">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="sticky-thead-admin">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody id="normaladminTbody">
                                        @foreach ($normaladmin as $n)
                                        <tr data-search="{{ strtolower($n->nama_admin . ' ' . $n->email_admin) }}">
                                            <td data-label="Nama">{{ $n->nama_admin }}</td>
                                            <td data-label="Email">{{ $n->email_admin }}</td>
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

                            <hr>

                            <h5 class="fw-bold">Tambah Admin Baru</h5>

                            <form action="{{ route('superadmin.normaladmin.store') }}" method="POST">
                                @csrf
                                <label class="form-label fw-bold">Nama Admin:</label>
                                <input type="text" name="nama_admin" class="form-control mb-3" required>

                                <label class="form-label fw-bold">Email Admin:</label>
                                <input type="email" name="email_admin" class="form-control mb-3" required>

                                <label class="form-label fw-bold">Password:</label>
                                <input type="password" name="password_admin" class="form-control mb-3" required>

                                <button class="btn btn-success w-100" type="submit">
                                    <i class="fas fa-save me-2"></i> Tambah Admin
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- ========================= MODALS ========================= -->

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
                                    <input type="text" name="nama_pimpinan" class="form-control mb-3" value="{{ $p->nama_pimpinan }}">

                                    <label class="form-label fw-bold">Jabatan:</label>
                                    <input type="text" name="jabatan_pimpinan" class="form-control mb-3" value="{{ $p->jabatan_pimpinan }}">
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
                                    <textarea name="video_keterangan" class="form-control mb-3" rows="3" required>{{ $v->video_keterangan }}</textarea>
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

                @foreach ($kegiatan as $k)
                <div class="modal fade" id="modalEditKegiatan-{{ $k->kegiatan_id }}" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Agenda</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('superadmin.kegiatan.update', $k->kegiatan_id) }}" method="POST">
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
                                    <textarea name="isi_text" class="form-control mb-3" rows="3" required>{{ $r->isi_text }}</textarea>
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
                                <h5 class="modal-title">Edit Normal Admin</h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('superadmin.normaladmin.update', $n->id_admin) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label fw-bold">Nama Admin:</label>
                                    <input type="text" name="nama_admin" class="form-control mb-3" value="{{ $n->nama_admin }}" required>

                                    <label class="form-label fw-bold">Email:</label>
                                    <input type="email" name="email_admin" class="form-control mb-3" value="{{ $n->email_admin }}" required>

                                    <label class="form-label fw-bold">Password (Kosongkan jika tidak diganti):</label>
                                    <input type="password" name="password_admin" class="form-control mb-3">
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
            const sections = document.querySelectorAll('section[id]');

            function setActiveSidebarItem() {
                let current = '';
                const scrollPosition = window.pageYOffset + 150;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                if (current) {
                    sidebarItems.forEach(item => {
                        item.classList.remove('active');
                        if (item.getAttribute('href') === '#' + current) item.classList.add('active');
                    });
                }
            }

            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(setActiveSidebarItem, 100);
            });

            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');

                    sidebarItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');

                    if (targetId.startsWith('#')) {
                        const targetSection = document.querySelector(targetId);
                        if (targetSection) {
                            e.preventDefault();
                            const offsetTop = targetSection.offsetTop - 100;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    }

                    if (window.innerWidth <= 992) sidebar.classList.remove('show');
                });
            });

            setActiveSidebarItem();
        })();

        // 🔥 UNIVERSAL PAGINATION & SEARCH FUNCTION
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
            const rowsPerPage = 4;

            const emptyRowClass = tableName + "-empty-row";
            const existingEmptyRows = Array.from(tbody.querySelectorAll("tr." + emptyRowClass));

            const firstDataRow = allDataRows.find(row => row.querySelectorAll("td").length > 0);
            const colCount = firstDataRow ? firstDataRow.querySelectorAll("td").length : 6;

            if (existingEmptyRows.length < rowsPerPage) {
                const rowsToAdd = rowsPerPage - existingEmptyRows.length;
                for (let i = 0; i < rowsToAdd; i++) {
                    const emptyRow = document.createElement("tr");
                    emptyRow.className = emptyRowClass;
                    emptyRow.innerHTML = `<td colspan="${colCount}" style="height: 60px; border: none;"></td>`;
                    tbody.appendChild(emptyRow);
                }
            }

            const emptyRows = Array.from(tbody.querySelectorAll("tr." + emptyRowClass));

            function showPage(page) {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

                if (page < 0) page = 0;
                if (totalPages === 0) page = 0;
                if (page >= totalPages && totalPages > 0) page = totalPages - 1;

                currentPage = page;

                allDataRows.forEach(row => row.style.display = 'none');
                emptyRows.forEach(row => row.style.display = 'none');

                const startIndex = currentPage * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;
                const rowsToShow = filteredRows.slice(startIndex, endIndex);

                rowsToShow.forEach(row => row.style.display = 'table-row');

                const emptyRowsToShow = emptyRows.slice(0, rowsPerPage - rowsToShow.length);
                emptyRowsToShow.forEach(row => row.style.display = 'table-row');

                if (prevBtn && nextBtn) {
                    prevBtn.disabled = (currentPage === 0);
                    nextBtn.disabled = (currentPage >= totalPages - 1 || totalPages === 0);

                    prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
                    prevBtn.style.cursor = prevBtn.disabled ? 'not-allowed' : 'pointer';
                    nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
                    nextBtn.style.cursor = nextBtn.disabled ? 'not-allowed' : 'pointer';
                }
            }

            function performSearch() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';

                if (searchTerm === '') filteredRows = [...allDataRows];
                else {
                    filteredRows = allDataRows.filter(row => {
                        const searchData = row.getAttribute('data-search') || '';
                        return searchData.includes(searchTerm);
                    });
                }

                currentPage = 0;
                showPage(0);
            }

            if (searchInput) searchInput.addEventListener('input', performSearch);

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 0) showPage(currentPage - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                    if (currentPage < totalPages - 1) showPage(currentPage + 1);
                });
            }

            showPage(0);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tables = ['profil', 'video', 'agenda', 'runningtext', 'normaladmin'];

            tables.forEach(tableName => {
                initTablePagination(tableName);

                const clearBtn = document.getElementById(tableName + 'ClearSearch');
                const searchInput = document.getElementById(tableName + 'Search');

                if (clearBtn && searchInput) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        initTablePagination(tableName);
                    });
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
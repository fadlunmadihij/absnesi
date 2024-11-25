<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="sidebar-brand-icon rotate-n-15">
      </div>
      <div class="sidebar-brand-text mx-3">SMA 3 Sumenep</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>

    <li class="nav-item {{ Request::is('profile') ? 'active' : '' }}">
      <a class="nav-link" href="/profile">
        <i class="fas fa-fw fa-user"></i>
        <span>Profile</span></a>
    </li>

    <li class="nav-item {{ Request::is('kelas') ? 'active' : '' }}">
      <a class="nav-link" href="/kelas">
        <i class="fas fa-fw fa-school"></i>
        <span>Kelas</span></a>
    </li>

    <li class="nav-item {{ Request::is('siswa') ? 'active' : '' }}">
      <a class="nav-link" href="/siswa">
        <i class="fas fa-fw fa-user-graduate"></i>
        <span>Siswa</span></a>
    </li>

    <li class="nav-item {{ Request::is('absen') ? 'active' : '' }}">
      <a class="nav-link" href="/absen">
        <i class="fas fa-fw fa-calendar-check"></i>
        <span>Absensi</span></a>
    </li>

    <li class="nav-item {{ Request::is('keterangan') ? 'active' : '' }}">
      <a class="nav-link" href="/keterangan">
        <i class="fas fa-fw fa-info-circle"></i>
        <span>Keterangan</span></a>
    </li>

    <li class="nav-item {{ Request::is('rekap') ? 'active' : '' }}">
      <a class="nav-link" href="/rekap">
        <i class="fas fa-fw fa-file-alt"></i>
        <span>Rekap</span></a>
    </li>

    <li class="nav-item dropdown {{ Request::is('kriteria*') || Request::is('indikator*') || Request::is('example*') || Request::is('tahap1') || Request::is('tahap2*') || Request::is('normalisasi*') || Request::is('exRanking*') ? 'active' : '' }}">
        <a class="nav-link dropdown-toggle" href="#" id="perhitunganDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Perhitungan</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="perhitunganDropdown">
            <a class="dropdown-item {{ Request::is('indikator') ? 'active' : '' }}" href="/indikator">
                <i class="fas fa-fw fa-file-alt"></i> Indikator
            </a>
            <a class="dropdown-item {{ Request::is('kriteria') ? 'active' : '' }}" href="/kriteria">
                <i class="fas fa-fw fa-file-alt"></i> Kriteria
            </a>
            <a class="dropdown-item {{ Request::is('example') ? 'active' : '' }}" href="/example">
                <i class="fas fa-fw fa-file-alt"></i> Example Data Siswa
            </a>
            <a class="dropdown-item {{ Request::is('tahap1') ? 'active' : '' }}" href="/tahap1">
                <i class="fas fa-fw fa-file-alt"></i> Tahap Analisis 1
            </a>
            <a class="dropdown-item {{ Request::is('tahap2') ? 'active' : '' }}" href="/tahap2">
                <i class="fas fa-fw fa-file-alt"></i> Tahap Analisis 2
            </a>
            <a class="dropdown-item {{ Request::is('normalisasi') ? 'active' : '' }}" href="/normalisasi">
                <i class="fas fa-fw fa-file-alt"></i> Matrik Ternormalisasi
            </a>
            <a class="dropdown-item {{ Request::is('exRanking') ? 'active' : '' }}" href="/exRanking">
                <i class="fas fa-fw fa-file-alt"></i> Hasil Perankingan
            </a>
        </div>
    </li>


    <li class="nav-item {{ Request::is('ranking') ? 'active' : '' }}">
      <a class="nav-link" href="/ranking">
        <i class="fas fa-fw fa-sort-numeric-up"></i>
        <span>Ranking</span></a>
    </li>

    <li class="nav-item {{ Request::is('scan') ? 'active' : '' }}">
      <a class="nav-link" href="/scan">
        <i class="fas fa-fw fa-qrcode"></i>
        <span>Scan</span></a>
    </li>

    <li class="nav-item {{ Request::is('qr-code') ? 'active' : '' }}">
      <a class="nav-link" href="/qr-code">
        <i class="fas fa-fw fa-qrcode"></i>
        <span>QR Generate</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

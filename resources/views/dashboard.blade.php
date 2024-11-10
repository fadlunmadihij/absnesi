@extends('layouts.app')

{{-- @section('title', 'Dashboard - Laravel Admin Panel With Login and Registration') --}}

@section('contents')
  <div class="row">
   <h1 style="color: black"> Selamat Datang</h1>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-3">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        KELAS</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahKelas }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        SISWA</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sis }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Total Absensi
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $totalAbsen }} <!-- Menampilkan total absensi -->
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-user-check fa-2x text-gray-300"></i> <!-- Icon terkait absensi -->
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

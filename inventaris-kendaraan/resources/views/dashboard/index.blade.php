@extends('layouts.app')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Ringkasan inventaris kendaraan dinas operasional')

@section('content')
@php
    $cards = [
        ['id' => 'total_kendaraan', 'label' => 'Total Kendaraan', 'icon' => 'bi-car-front', 'tone' => 'primary'],
        ['id' => 'baik', 'label' => 'Kondisi Baik', 'icon' => 'bi-check-circle', 'tone' => 'success'],
        ['id' => 'rusak_ringan', 'label' => 'Rusak Ringan', 'icon' => 'bi-exclamation-triangle', 'tone' => 'warning'],
        ['id' => 'rusak_berat', 'label' => 'Rusak Berat', 'icon' => 'bi-x-octagon', 'tone' => 'danger'],
        ['id' => 'total_perangkat_daerah', 'label' => 'Perangkat Daerah', 'icon' => 'bi-building', 'tone' => 'info'],
        ['id' => 'total_pegawai', 'label' => 'Pegawai Terdaftar', 'icon' => 'bi-people', 'tone' => 'secondary'],
    ];
@endphp

<div class="row g-3 mb-3">
    @foreach ($cards as $c)
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon stat-{{ $c['tone'] }}"><i class="bi {{ $c['icon'] }}"></i></div>
                    <div>
                        <div class="fs-4 fw-semibold lh-1" id="stat-{{ $c['id'] }}">-</div>
                        <div class="text-body-secondary small mt-1">{{ $c['label'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">Kendaraan berdasarkan Kondisi</div>
            <div class="card-body"><div class="chart-box"><canvas id="chartKondisi"></canvas></div></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">Kendaraan berdasarkan Perangkat Daerah</div>
            <div class="card-body"><div class="chart-box"><canvas id="chartPerangkatDaerah"></canvas></div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>window.DASHBOARD_URL = @json(route('dashboard.data'));</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush

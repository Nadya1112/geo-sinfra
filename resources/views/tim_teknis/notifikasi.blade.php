@extends('layouts.app')
@section('title', 'Notifikasi | GEO-SINFRA')
@section('subtitle', 'Pusat Informasi')
@section('page_title', 'Notifikasi Sistem')

@section('content')
<div class="relative max-w-5xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Pusat Notifikasi</h1>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pantau semua aktivitas dan pembaruan sistem yang terkait dengan Anda.</p>
    </div>

    <!-- Livewire Component -->
    @livewire('tim-teknis.notifikasi-table')

</div>
@endsection

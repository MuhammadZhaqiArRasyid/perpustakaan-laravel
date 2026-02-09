@extends('layouts.app')

@section('content')
<style>
    /* Custom CSS untuk mempercantik */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        transform: scale(1.002);
    }
    .card { border-radius: 12px; overflow: hidden; }
    .btn { border-radius: 8px; font-weight: 500; }
    .form-control, .form-select { border-radius: 8px; padding: 0.6rem 1rem; }
    .badge { padding: 0.5em 0.8em; font-weight: 500; }
    .search-container {
        background: #fcfcfc;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 20px;
    }
</style>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 border-bottom-0">
            <div>
                <h4 class="mb-0 fw-bold text-dark">Katalog Perpustakaan</h4>
                <p class="text-muted small mb-0">Kelola dan telusuri koleksi buku Anda dengan mudah.</p>
            </div>

            @if (auth()->user()->role === 'admin')
                <a href="/buku/create" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Buku Baru
                </a>
            @endif
        </div>

        <div class="card-body">
            
            {{-- FILTER & SEARCH BAR --}}
            <div class="search-container mb-4">
                <form action="/buku" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1">Cari Buku</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Judul buku..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">Penulis</label>
                        <select name="penulis" class="form-select">
                            <option value="">Semua Penulis</option>
                            @foreach($daftar_penulis as $p)
                                <option value="{{ $p }}" {{ request('penulis') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">Penerbit</label>
                        <select name="penerbit" class="form-select">
                            <option value="">Semua Penerbit</option>
                            @foreach($daftar_penerbit as $p)
                                <option value="{{ $p }}" {{ request('penerbit') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-dark flex-grow-1">
                            <i class="bi bi-filter"></i>
                        </button>
                        @if(request('search') || request('penulis') || request('penerbit'))
                            <a href="/buku" class="btn btn-outline-danger" title="Reset Filter">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- FLASH MESSAGE --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold border-top" style="background: #fafafa">
                            <th class="ps-4 py-3">Buku & Penulis</th>
                            <th>Penerbit & Tahun</th>
                            <th class="text-center">Status Stok</th>
                            <th class="text-center" style="width: 250px">Kelola</th>
                        </tr>
                    </thead>

                    <tbody class="border-top-0">
                        @forelse ($buku as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-buku me-3 bg-primary text-white d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 50px;">
                                            <i class="bi bi-book"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $item->judul }}</div>
                                            <div class="text-muted small"><i class="bi bi-person me-1"></i>{{ $item->penulis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->penerbit }}</div>
                                    <div class="text-muted small">{{ $item->tahun }}</div>
                                </td>
                                <td class="text-center">
                                    @if($item->stok > 5)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                            ● {{ $item->stok }} Tersedia
                                        </span>
                                    @elseif($item->stok > 0)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                            ● {{ $item->stok }} Menipis
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                            ● Habis
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if (auth()->user()->role === 'user')
                                            @if ($item->stok > 0)
                                                <a href="/pinjam/{{ $item->id }}" class="btn btn-sm btn-primary px-4 shadow-sm">
                                                    Pinjam
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-light disabled text-muted border">Habis</button>
                                            @endif
                                        @endif

                                        @if (auth()->user()->role === 'admin')
                                            <a href="/buku/{{ $item->id }}/edit" class="btn btn-sm btn-outline-secondary px-3">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                            <form action="/buku/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus buku ini?')" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                    <i class="bi bi-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-journal-x fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">Tidak ada buku yang sesuai dengan kriteria filter.</p>
                                    <a href="/buku" class="btn btn-sm btn-outline-primary px-4">Tampilkan Semua</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
@endsection
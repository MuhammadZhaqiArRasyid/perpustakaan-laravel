@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-subtle text-primary rounded-circle me-3">
                    <i class="bi bi-journal-text fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Data Transaksi</h4>
                    <small class="text-muted">
                        Total transaksi: {{ $transaksis->count() }}
                    </small>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th>User</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transaksis as $t)
                        <tr>
                            <td class="fw-semibold">
                                {{ $t->user->name }}
                            </td>

                            <td>
                                {{ $t->buku->judul }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d M Y') }}
                            </td>

                            <td class="text-center">
                                @if ($t->status === 'dipinjam')
                                    <span class="badge bg-warning-subtle text-warning px-3 rounded-pill">
                                        Dipinjam
                                    </span>
                                @elseif ($t->status === 'dikembalikan')
                                    <span class="badge bg-success-subtle text-success px-3 rounded-pill">
                                        Dikembalikan
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 rounded-pill">
                                        Hilang
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">
                                @if ($t->denda > 0)
                                    <span class="fw-bold text-danger">
                                        Rp {{ number_format($t->denda) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($t->status === 'dipinjam')

                                    <form action="/admin/transaksi/{{ $t->id }}/kembali"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin buku sudah dikembalikan?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success px-3">
                                            <i class="bi bi-check-lg me-1"></i> Kembali
                                        </button>
                                    </form>

                                    <form action="/admin/transaksi/{{ $t->id }}/hilang"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Tandai buku sebagai hilang? Denda Rp45.000')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger px-3">
                                            <i class="bi bi-x-circle me-1"></i> Hilang
                                        </button>
                                    </form>

                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada transaksi
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
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table thead th {
        font-size: .75rem;
        letter-spacing: .05em;
    }
    .btn {
        border-radius: 8px;
    }
</style>
@endpush
@endsection

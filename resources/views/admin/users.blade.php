@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-subtle text-primary rounded-circle me-3">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Daftar User</h4>
                    <small class="text-muted">
                        Total user: {{ $users->count() }}
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
                            <th style="width: 60px">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th class="text-center">Role</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td class="text-muted">
                                {{ $user->email }}
                            </td>

                            <td class="text-center">
                                @if ($user->role === 'admin')
                                    <span class="badge bg-danger-subtle text-danger px-3 rounded-pill">
                                        Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 rounded-pill">
                                        User
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada user
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
        letter-spacing: .05em;
        font-size: .75rem;
    }
</style>
@endpush
@endsection

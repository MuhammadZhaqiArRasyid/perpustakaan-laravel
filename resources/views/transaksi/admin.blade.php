@extends('layouts.app')

@section('content')
<h4 class="mb-4">📋 Data Transaksi</h4>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>User</th>
            <th>Buku</th>
            <th>Pinjam</th>
            <th>Tempo</th>
            <th>Status</th>
            <th>Denda</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksis as $t)
        <tr>
            <td>{{ $t->user->name }}</td>
            <td>{{ $t->buku->judul }}</td>
            <td>{{ $t->tanggal_pinjam }}</td>
            <td>{{ $t->tanggal_jatuh_tempo }}</td>
            <td>{{ $t->status }}</td>
            <td class="text-danger fw-bold">
                Rp {{ number_format($t->denda) }}
            </td>
            <td>
                @if($t->status === 'dipinjam')
                <form action="/admin/kembali/{{ $t->id }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success">Kembali</button>
                </form>

                <form action="/admin/hilang/{{ $t->id }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-danger">Hilang</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

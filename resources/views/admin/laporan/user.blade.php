<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:6px; }
    </style>
</head>
<body>

<h3>Laporan Peminjaman</h3>
<p>
    Nama: {{ $user->name }} <br>
    Email: {{ $user->email }}
</p>

<table>
    <thead>
        <tr>
            <th>Buku</th>
            <th>Pinjam</th>
            <th>Tempo</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksis as $t)
        <tr>
            <td>{{ $t->buku->judul }}</td>
            <td>{{ $t->tanggal_pinjam }}</td>
            <td>{{ $t->tanggal_jatuh_tempo }}</td>
            <td>{{ $t->status }}</td>
            <td>Rp {{ number_format($t->denda) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

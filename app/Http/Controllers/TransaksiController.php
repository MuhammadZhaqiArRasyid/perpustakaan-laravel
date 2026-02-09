<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // USER PINJAM BUKU
    public function pinjam($id)
{
    $buku = Buku::findOrFail($id);

    if ($buku->stok < 1) {
        return back()->with('error', 'Stok buku habis');
    }

    Transaksi::create([
        'user_id' => Auth::id(),
        'buku_id' => $id,
        'tanggal_pinjam' => now(),
        'tanggal_jatuh_tempo' => now()->addDays(7), // ⬅️ INI PENTING
        'status' => 'dipinjam',
        'denda' => 0
    ]);


    $buku->decrement('stok');

    return back()->with('success', 'Buku berhasil dipinjam');
}


    // USER KEMBALIKAN BUKU
    public function kembali($id)
    {
        $transaksi = Transaksi::with('buku')->findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $hariIni = now();
        $tempo   = $transaksi->tanggal_jatuh_tempo;

        $denda = 0;

        if ($hariIni->gt($tempo)) {
            $hariTerlambat = $tempo->diffInDays($hariIni);
            $denda = $hariTerlambat * 1000; // ⬅️ 1.000 / HARI
        }

        $transaksi->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'denda' => $denda
        ]);

        $transaksi->buku->increment('stok');

        return back()->with(
            'success',
            'Buku dikembalikan. Denda: Rp ' . number_format($denda)
        );
    }


    // RIWAYAT USER
    public function riwayat()
    {
        $transaksis = Transaksi::with('buku')
            ->where('user_id', Auth::id())
            ->get();

        return view('transaksi.riwayat', compact('transaksis'));
    }
    public function hilang($id)
    {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $transaksi = Transaksi::findOrFail($id);

        $transaksi->update([
            'status' => 'hilang',
            'denda' => 45000 // ⬅️ DENDA HILANG
        ]);

        return back()->with('success', 'Buku ditandai hilang. Denda Rp 45.000');
    }
    public function adminUsers()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $users = User::all();

        return view('admin.users', compact('users'));
    }
    public function adminTransaksi()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }


        $transaksis = Transaksi::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.transaksi', compact('transaksis'));
    }
    // public function adminIndex()
    // {
    //     if (Auth::user()->role !== 'admin') {
    //         abort(403);
    //     }

    //     $transaksis = Transaksi::with(['user', 'buku'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('admin.transaksi', compact('transaksis'));
    // }
    public function laporanUser(User $user)
    {
        $transaksis = $user->transaksis()
            ->with('buku')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.user', [
            'user' => $user,
            'transaksis' => $transaksis
        ]);

        return $pdf->download('laporan-'.$user->name.'.pdf');
    }



}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\User;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{


public function index()
{
    $user = Auth::user();

    if ($user->role === 'admin') {
        // ADMIN STATS
        $data = [
            'totalBuku' => Buku::count(),
            'totalUser' => User::where('role', 'user')->count(),
            'transaksiAktif' => Transaksi::where('status', 'dipinjam')->count(),
            'totalDenda' => Transaksi::sum('denda'),
        ];
    } else {
        // USER STATS
        $data = [
            'dipinjam' => Transaksi::where('user_id', $user->id)
                            ->where('status', 'dipinjam')->count(),

            'telat' => Transaksi::where('user_id', $user->id)
                        ->where('status', 'dipinjam')
                        ->whereDate('tanggal_jatuh_tempo', '<', now())
                        ->count(),

            'totalDenda' => Transaksi::where('user_id', $user->id)->sum('denda'),
        ];
    }

    return view('dashboard', compact('data'));
}

}

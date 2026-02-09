<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $data = [
                'totalBuku'       => Buku::count(),
                'totalUser'       => User::where('role', 'user')->count(),
                'transaksiAktif'  => Transaksi::where('status', 'dipinjam')->count(),
                'totalDenda'      => Transaksi::sum('denda'),
            ];
        } else {
            $data = [
                'dipinjam'    => Transaksi::where('user_id', $user->id)
                                    ->where('status', 'dipinjam')->count(),
                'telat'       => Transaksi::where('user_id', $user->id)
                                    ->where('status', 'telat')->count(),
                'totalDenda'  => Transaksi::where('user_id', $user->id)->sum('denda'),
            ];
        }

        return view('dashboard', compact('data'));
    }
}

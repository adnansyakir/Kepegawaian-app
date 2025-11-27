<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Dosen;
use App\Models\Teknisi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::count();
        $totalDosen = Dosen::count();
        $totalTeknisi = Teknisi::count();

        return view('direktur.dashboard', compact(
            'totalKaryawan',
            'totalDosen',
            'totalTeknisi'
        ));
    }
}
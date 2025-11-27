<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Dosen;
use App\Models\Teknisi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::count();
        $totalDosen = Dosen::count();
        $totalTeknisi = Teknisi::count();
        $totalUser = User::count();

        return view('admin.dashboard', compact(
            'totalKaryawan',
            'totalDosen',
            'totalTeknisi',
            'totalUser'
        ));
    }
}
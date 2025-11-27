<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::latest()->paginate(10);
        return view('direktur.karyawan.index', compact('karyawan'));
    }

    public function show(Karyawan $karyawan)
    {
        return view('direktur.karyawan.show', compact('karyawan'));
    }
}
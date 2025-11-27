<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Dosen;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::latest()->paginate(10);
        return view('direktur.dosen.index', compact('dosen'));
    }

    public function show(Dosen $dosen)
    {
        return view('direktur.dosen.show', compact('dosen'));
    }
}
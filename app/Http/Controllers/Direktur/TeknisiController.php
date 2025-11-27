<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisi = Teknisi::latest()->paginate(10);
        return view('direktur.teknisi.index', compact('teknisi'));
    }

    public function show(Teknisi $teknisi)
    {
        return view('direktur.teknisi.show', compact('teknisi'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $golongans = Golongan::latest()->paginate(10);
        return view('admin.golongan.index', compact('golongans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.golongan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|array',
            'nama.*' => 'required|string',
        ]);

        foreach ($validated['nama'] as $nama) {
            Golongan::create(['nama' => $nama]);
        }

        $count = count($validated['nama']);
        return redirect()->route('admin.golongan.index')
            ->with('success', "$count Golongan berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Golongan $golongan)
    {
        return view('admin.golongan.show', compact('golongan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Golongan $golongan)
    {
        return view('admin.golongan.edit', compact('golongan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $golongan->update($request->all());

        return redirect()->route('admin.golongan.index')
            ->with('success', 'Golongan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Golongan $golongan)
    {
        $golongan->delete();

        return redirect()->route('admin.golongan.index')
            ->with('success', 'Golongan berhasil dihapus.');
    }
}

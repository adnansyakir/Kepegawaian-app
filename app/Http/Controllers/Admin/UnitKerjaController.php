<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unitKerjas = UnitKerja::latest()->paginate(10);
        return view('admin.unit-kerja.index', compact('unitKerjas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.unit-kerja.create');
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
            UnitKerja::create(['nama' => $nama]);
        }

        $count = count($validated['nama']);
        return redirect()->route('admin.unit-kerja.index')
            ->with('success', "$count Unit Kerja berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitKerja $unitKerja)
    {
        return view('admin.unit-kerja.show', compact('unitKerja'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitKerja $unitKerja)
    {
        return view('admin.unit-kerja.edit', compact('unitKerja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitKerja $unitKerja)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $unitKerja->update($request->all());

        return redirect()->route('admin.unit-kerja.index')
            ->with('success', 'Unit Kerja berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitKerja $unitKerja)
    {
        $unitKerja->delete();

        return redirect()->route('admin.unit-kerja.index')
            ->with('success', 'Unit Kerja berhasil dihapus.');
    }
}

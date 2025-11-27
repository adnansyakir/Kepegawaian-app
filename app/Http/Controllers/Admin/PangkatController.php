<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pangkat;
use Illuminate\Http\Request;

class PangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pangkats = Pangkat::latest()->paginate(10);
        return view('admin.pangkat.index', compact('pangkats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pangkat.create');
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
            Pangkat::create(['nama' => $nama]);
        }

        $count = count($validated['nama']);
        return redirect()->route('admin.pangkat.index')
            ->with('success', "$count Pangkat berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Pangkat $pangkat)
    {
        return view('admin.pangkat.show', compact('pangkat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pangkat $pangkat)
    {
        return view('admin.pangkat.edit', compact('pangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pangkat $pangkat)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $pangkat->update($request->all());

        return redirect()->route('admin.pangkat.index')
            ->with('success', 'Pangkat berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pangkat $pangkat)
    {
        $pangkat->delete();

        return redirect()->route('admin.pangkat.index')
            ->with('success', 'Pangkat berhasil dihapus.');
    }
}

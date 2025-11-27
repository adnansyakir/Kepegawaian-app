<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = Jurusan::withCount('prodis')->latest()->paginate(10);
        return view('admin.jurusan.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jurusan.create');
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
            Jurusan::create(['nama' => $nama]);
        }

        $count = count($validated['nama']);
        return redirect()->route('admin.jurusan.index')
            ->with('success', "$count Jurusan berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan)
    {
        $jurusan->load('prodis');
        return view('admin.jurusan.show', compact('jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $jurusan->update($request->all());

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}

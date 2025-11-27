<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::with('jurusan')->latest()->paginate(10);
        return view('admin.prodi.index', compact('prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = Jurusan::all();
        return view('admin.prodi.create', compact('jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'nama' => 'required|array',
            'nama.*' => 'required|string',
        ]);

        foreach ($validated['nama'] as $nama) {
            Prodi::create([
                'jurusan_id' => $validated['jurusan_id'],
                'nama' => $nama
            ]);
        }

        $count = count($validated['nama']);
        return redirect()->route('admin.prodi.index')
            ->with('success', "$count Program Studi berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Prodi $prodi)
    {
        $prodi->load('jurusan');
        return view('admin.prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi)
    {
        $jurusans = Jurusan::all();
        return view('admin.prodi.edit', compact('prodi', 'jurusans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'nama' => 'required',
        ]);

        $prodi->update($request->all());

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program Studi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImport;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::query();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dosen', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('jurusan', 'like', '%' . $search . '%')
                  ->orWhere('prodi_id', 'like', '%' . $search . '%');
            });
        }
        
        // Order by nama_dosen (alphabetically)
        $dosen = $query->orderBy('nama_dosen', 'asc')->paginate(10);
        
        return view('admin.dosen.index', compact('dosen'));
    }

    public function create()
    {
        $jurusans = \App\Models\Jurusan::orderBy('nama')->get();
        $prodis = \App\Models\Prodi::orderBy('nama')->get();
        $pangkats = \App\Models\Pangkat::orderBy('nama')->get();
        $golongans = \App\Models\Golongan::orderBy('nama')->get();
        
        return view('admin.dosen.create', compact('jurusans', 'prodis', 'pangkats', 'golongans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nip' => 'nullable|string|max:50',
            'tmt_cpns_ppk' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:255',
            'golongan' => 'nullable|string|max:50',
            'tmt' => 'nullable|date',
            'jf' => 'nullable|string|max:50',
            'tmt_jf' => 'nullable|date',
            'status_kepegawaian' => 'nullable|string|max:255',
            'tahun_purna_tugas' => 'nullable|date',
            'nuptk' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('dosen', 'public');
            $validated['foto'] = $fotoPath;
        }

        Dosen::create($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen)
    {
        return view('admin.dosen.show', compact('dosen'));
    }

    public function edit(Dosen $dosen)
    {
        $jurusans = \App\Models\Jurusan::orderBy('nama')->get();
        $prodis = \App\Models\Prodi::orderBy('nama')->get();
        $pangkats = \App\Models\Pangkat::orderBy('nama')->get();
        $golongans = \App\Models\Golongan::orderBy('nama')->get();
        
        return view('admin.dosen.edit', compact('dosen', 'jurusans', 'prodis', 'pangkats', 'golongans'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nip' => 'nullable|string|max:50' . $dosen->id,
            'tmt_cpns_ppk' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:255',
            'golongan' => 'nullable|string|max:50',
            'tmt' => 'nullable|date',
            'jf' => 'nullable|string|max:50',
            'tmt_jf' => 'nullable|date',
            'status_kepegawaian' => 'nullable|string|max:255',
            'tahun_purna_tugas' => 'nullable|date',
            'nuptk' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $fotoPath = $request->file('foto')->store('dosen', 'public');
            $validated['foto'] = $fotoPath;
        }

        $dosen->update($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        // Delete foto if exists
        if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
            Storage::disk('public')->delete($dosen->foto);
        }
        
        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:51200',
        ]);

        try {
            // Hitung total dosen sebelum import
            $beforeCount = Dosen::count();
            
            $import = new DosenImport;
            Excel::import($import, $request->file('file'));

            // Hitung total dosen setelah import
            $afterCount = Dosen::count();
            $actualCreated = $afterCount - $beforeCount;
            
            $updatedCount = $import->getUpdatedCount();
            $skippedCount = $import->getSkippedCount();
            $failedRows = $import->getFailedRows();
            $totalProcessed = $import->getCreatedCount() + $updatedCount + $skippedCount + count($failedRows);
            
            $message = "Berhasil import {$totalProcessed} baris data: {$actualCreated} data baru ditambahkan";
            
            if ($updatedCount > 0) {
                $message .= ", {$updatedCount} data diupdate";
            }
            
            if ($skippedCount > 0) {
                $message .= ", {$skippedCount} baris dikosongkan (baris kosong/tidak ada nama)";
            }
            
            $message .= ".";
            
            if (count($failedRows) > 0) {
                $failedCount = count($failedRows);
                $message .= " {$failedCount} data gagal diimport.";
                
                // Simpan detail data yang gagal ke session untuk ditampilkan
                session()->flash('failed_imports', $failedRows);
            }

            return redirect()->route('admin.dosen.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.dosen.index')
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
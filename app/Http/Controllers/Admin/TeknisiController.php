<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeknisiImport;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Teknisi::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('unit_kerja', 'like', '%' . $search . '%')
                  ->orWhere('status_kepegawaian', 'like', '%' . $search . '%');
            });
        }

        $teknisi = $query->orderBy('nama', 'asc')->paginate(10);
        return view('admin.teknisi.index', compact('teknisi'));
    }

    public function create()
    {
        $pangkats = \App\Models\Pangkat::all();
        $golongans = \App\Models\Golongan::all();
        return view('admin.teknisi.create', compact('pangkats', 'golongans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'nullable|string|max:50|unique:teknisi,nip',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tmt_cpns_ppk' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:10',
            'gol' => 'nullable|string|max:50',
            'tmt_gol' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'kelas_jabatan' => 'nullable|string|max:50',
            'tahun_purna_tugas' => 'nullable|string|max:10',
            'status_kepegawaian' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('teknisi', 'public');
        }

        Teknisi::create($validated);

        return redirect()->route('admin.teknisi.index')
            ->with('success', 'Data teknisi berhasil ditambahkan.');
    }

    public function show(Teknisi $teknisi)
    {
        return view('admin.teknisi.show', compact('teknisi'));
    }

    public function edit(Teknisi $teknisi)
    {
        $pangkats = \App\Models\Pangkat::all();
        $golongans = \App\Models\Golongan::all();
        return view('admin.teknisi.edit', compact('teknisi', 'pangkats', 'golongans'));
    }

    public function update(Request $request, Teknisi $teknisi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'nullable|string|max:50|unique:teknisi,nip,' . $teknisi->id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tmt_cpns_ppk' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:10',
            'gol' => 'nullable|string|max:50',
            'tmt_gol' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'kelas_jabatan' => 'nullable|string|max:50',
            'tahun_purna_tugas' => 'nullable|string|max:10',
            'status_kepegawaian' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($teknisi->foto) {
                Storage::disk('public')->delete($teknisi->foto);
            }
            $validated['foto'] = $request->file('foto')->store('teknisi', 'public');
        }

        $teknisi->update($validated);

        return redirect()->route('admin.teknisi.index')
            ->with('success', 'Data teknisi berhasil diperbarui.');
    }

    public function destroy(Teknisi $teknisi)
    {
        // Delete foto if exists
        if ($teknisi->foto) {
            Storage::disk('public')->delete($teknisi->foto);
        }
        
        $teknisi->delete();

        return redirect()->route('admin.teknisi.index')
            ->with('success', 'Data teknisi berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:51200',
        ]);

        try {
            // Hitung total teknisi sebelum import
            $beforeCount = Teknisi::count();
            
            $import = new TeknisiImport;
            Excel::import($import, $request->file('file'));

            // Hitung total teknisi setelah import
            $afterCount = Teknisi::count();
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

            return redirect()->route('admin.teknisi.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.teknisi.index')
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
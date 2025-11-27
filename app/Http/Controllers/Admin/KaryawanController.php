<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Imports\KaryawanImport;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('unit_kerja', 'like', '%' . $search . '%');
            });
        }
        
        // Order by nama (alphabetically)
        $karyawan = $query->orderBy('nama', 'asc')->paginate(10);
        
        return view('admin.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $unitKerjas = \App\Models\UnitKerja::orderBy('nama')->get();
        $pangkats = \App\Models\Pangkat::orderBy('nama')->get();
        $golongans = \App\Models\Golongan::orderBy('nama')->get();
        
        return view('admin.karyawan.create', compact('unitKerjas', 'pangkats', 'golongans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'required|string|max:50|unique:karyawan,nip',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'unit_kerja' => 'nullable|string|max:255',
            'penempatan_kerja' => 'nullable|string|max:255',
            'tmt_pns_p3k' => 'nullable|date',
            'status_kepegawaian' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:255',
            'gol' => 'nullable|string|max:50',
            'tmt_gol' => 'nullable|date',
            'kelas_jabatan' => 'nullable|string|max:50',
            'tahun_purna_tugas' => 'nullable|date',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
            $validated['foto'] = $fotoPath;
        }

        Karyawan::create($validated);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(Karyawan $karyawan)
    {
        return view('admin.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $unitKerjas = \App\Models\UnitKerja::orderBy('nama')->get();
        $pangkats = \App\Models\Pangkat::orderBy('nama')->get();
        $golongans = \App\Models\Golongan::orderBy('nama')->get();
        
        return view('admin.karyawan.edit', compact('karyawan', 'unitKerjas', 'pangkats', 'golongans'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'required|string|max:50|unique:karyawan,nip,' . $karyawan->id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'unit_kerja' => 'nullable|string|max:255',
            'penempatan_kerja' => 'nullable|string|max:255',
            'tmt_pns_p3k' => 'nullable|date',
            'status_kepegawaian' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:255',
            'gol' => 'nullable|string|max:50',
            'tmt_gol' => 'nullable|date',
            'kelas_jabatan' => 'nullable|string|max:50',
            'tahun_purna_tugas' => 'nullable|date',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
            $validated['foto'] = $fotoPath;
        }

        $karyawan->update($validated);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $filePath = $file->getRealPath();
            
            $importer = new KaryawanImport();
            $result = $importer->import($filePath);
            
            if ($result['success'] > 0) {
                $message = "Berhasil mengimpor {$result['success']} data karyawan.";
                if (!empty($result['errors'])) {
                    $message .= " Terdapat " . count($result['errors']) . " baris dengan error.";
                    // Store errors in session to display details
                    session()->flash('import_errors', $result['errors']);
                }
                return redirect()->route('admin.karyawan.index')
                    ->with('success', $message);
            } else {
                // Store errors in session to display details
                session()->flash('import_errors', $result['errors']);
                return redirect()->route('admin.karyawan.index')
                    ->with('error', 'Gagal mengimpor data. ' . count($result['errors']) . ' baris dengan error.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.karyawan.index')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
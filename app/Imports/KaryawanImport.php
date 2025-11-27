<?php

namespace App\Imports;

use App\Models\Karyawan;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class KaryawanImport
{
    public function import($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Skip header row
        $header = array_shift($rows);
        
        $imported = 0;
        $errors = [];
        
        foreach ($rows as $index => $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            $rowNumber = $index + 2; // +2 because of header and 0-index
            
            // Map row data to associative array based on Excel columns
            // A=No, B=Nama, C=Jenis Kelamin, D=NIP, E=Tempat Lahir, F=Tanggal Lahir, 
            // G=Unit Kerja/Jurusan, H=TMT PNS/P3K/Honorer/THL, I=Status (PNS/P3), 
            // J=Pendidikan Terakhir, K=Tahun Lulus, L=Pangkat, M=GOL, N=TMT Gol, 
            // O=Kelas Jabatan, P=TAHUN PURNA TUGAS
            
            // Convert jenis kelamin L/P to Laki-laki/Perempuan
            $jenisKelamin = $row[2] ?? null;
            if ($jenisKelamin === 'L' || strtoupper($jenisKelamin) === 'L') {
                $jenisKelamin = 'Laki-laki';
            } elseif ($jenisKelamin === 'P' || strtoupper($jenisKelamin) === 'P') {
                $jenisKelamin = 'Perempuan';
            }
            
            // Normalize Status Kepegawaian
            $statusKepegawaian = $row[8] ?? null;
            if ($statusKepegawaian) {
                $statusKepegawaian = trim($statusKepegawaian);
                // Map common variations
                $statusMap = [
                    'PNS' => 'PNS',
                    'CPNS' => 'CPNS',
                    'PPPK' => 'PPPK',
                    'PPPK NEW' => 'PPPK New',
                    'PPPK PARUH WAKTU' => 'PPPK Paruh Waktu',
                    'HONORER' => 'Honorer',
                    'THL' => 'THL',
                ];
                $statusUpper = strtoupper($statusKepegawaian);
                if (isset($statusMap[$statusUpper])) {
                    $statusKepegawaian = $statusMap[$statusUpper];
                }
            }
            
            // Convert Kelas Jabatan to string
            $kelasJabatan = $row[14] ?? null;
            if ($kelasJabatan !== null) {
                $kelasJabatan = (string) $kelasJabatan;
            }
            
            // Helper function to convert Excel date to MySQL date format
            $convertDate = function($value) {
                if (empty($value)) {
                    return null;
                }
                
                // If numeric (Excel serial date)
                if (is_numeric($value)) {
                    try {
                        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                        return $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        return null;
                    }
                }
                
                // Try to parse various date formats
                $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/y', 'd-m-y'];
                foreach ($formats as $format) {
                    $date = \DateTime::createFromFormat($format, $value);
                    if ($date !== false) {
                        return $date->format('Y-m-d');
                    }
                }
                
                return null;
            };
            
            $data = [
                'nama' => $row[1] ?? null,  // B: Nama
                'jenis_kelamin' => $jenisKelamin,  // C: Jenis Kelamin (converted from L/P)
                'nip' => $row[3] ?? null,  // D: NIP
                'tempat_lahir' => $row[4] ?? null,  // E: Tempat Lahir
                'tanggal_lahir' => $convertDate($row[5]),  // F: Tanggal Lahir
                'unit_kerja' => $row[6] ?? null,  // G: Unit Kerja/Jurusan
                'tmt_pns_p3k' => $convertDate($row[7]),  // H: TMT PNS/P3K/Honorer/THL
                'status_kepegawaian' => $statusKepegawaian,  // I: Status (normalized)
                'pendidikan_terakhir' => $row[9] ?? null,  // J: Pendidikan Terakhir
                'tahun_lulus' => $row[10] ?? null,  // K: Tahun Lulus
                'pangkat' => $row[11] ?? null,  // L: Pangkat
                'gol' => $row[12] ?? null,  // M: GOL
                'tmt_gol' => $convertDate($row[13]),  // N: TMT Gol
                'kelas_jabatan' => $kelasJabatan,  // O: Kelas Jabatan (converted to string)
                'tahun_purna_tugas' => $convertDate($row[15]),  // P: TAHUN PURNA TUGAS
                'jabatan' => null,  // Tidak ada di Excel
            ];
            
            // Skip if nama is empty (completely empty row)
            if (empty($data['nama'])) {
                continue;
            }
            
            // Validate only critical fields - make it very permissive
            $validationRules = [
                'nama' => 'nullable|string|max:255',
                'nip' => 'nullable|string|max:50',
            ];
            
            // Only validate if values are present
            if (!empty($data['jenis_kelamin'])) {
                $validationRules['jenis_kelamin'] = 'in:Laki-laki,Perempuan';
            }
            if (!empty($data['status_kepegawaian'])) {
                $validationRules['status_kepegawaian'] = 'in:CPNS,PNS,PPPK,PPPK New,PPPK Paruh Waktu,Honorer,THL';
            }
            if (!empty($data['kelas_jabatan'])) {
                $validationRules['kelas_jabatan'] = 'in:1,3,5,6,7,8,9,12';
            }
            
            $validator = Validator::make($data, $validationRules);
            
            if ($validator->fails()) {
                $errors[] = "Baris {$rowNumber} ({$data['nama']}): " . implode(', ', $validator->errors()->all());
                continue;
            }
            
            // Handle duplicate NIP
            if (!empty($data['nip'])) {
                $existingNip = Karyawan::where('nip', $data['nip'])->first();
                if ($existingNip) {
                    $errors[] = "Baris {$rowNumber} ({$data['nama']}): NIP {$data['nip']} sudah ada (milik {$existingNip->nama})";
                    continue;
                }
            }
            
            // Create karyawan
            try {
                Karyawan::create($data);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNumber} ({$data['nama']}): " . $e->getMessage();
            }
        }
        
        return [
            'success' => $imported,
            'errors' => $errors,
        ];
    }
}

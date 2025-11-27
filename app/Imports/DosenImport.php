<?php

namespace App\Imports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DosenImport implements ToModel, WithStartRow, SkipsOnError
{
    use SkipsErrors;

    private $createdCount = 0;
    private $updatedCount = 0;
    private $skippedCount = 0;
    private $failedRows = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows or rows without nama_dosen
        if (empty($row[1]) || trim($row[1]) == '') {
            $this->skippedCount++;
            return null;
        }

        try {

        // Parse dates berdasarkan struktur Excel yang benar
        $tanggalLahir = $this->parseDate($row[4] ?? null);      // Column E: TANGGAL LAHIR
        $tmtCpnsPpk = $this->parseDate($row[6] ?? null);        // Column G: TMT CPNS/PPPK
        $tmt = $this->parseDate($row[11] ?? null);              // Column L: TMT
        $tmtJf = $this->parseDate($row[13] ?? null);            // Column N: TMT (TMT JF)
        $tahunPurnaTugas = $this->parseDate($row[15] ?? null);  // Column P: TAHUN PURNA TUGAS

        // Parse jenis kelamin
        $jenisKelamin = $this->parseJenisKelamin($row[2] ?? null); // Column C: JENIS KELAMIN
        
        // Clean NIP - convert "-" to null
        $nip = isset($row[5]) && trim($row[5]) != '' && trim($row[5]) != '-' ? trim($row[5]) : null; // Column F: NIP
        
        // Clean NUPTK
        $nuptk = isset($row[16]) && trim($row[16]) != '' ? trim($row[16]) : null; // Column Q: NUPTK

        // Prepare data
        $data = [
            'nama_dosen'            => trim($row[1]),       // B: NAMA DOSEN
            'jenis_kelamin'         => $jenisKelamin,       // C: JENIS KELAMIN
            'tempat_lahir'          => isset($row[3]) && trim($row[3]) != '' ? trim($row[3]) : null, // D: TEMPAT LAHIR
            'tanggal_lahir'         => $tanggalLahir,       // E: TANGGAL LAHIR
            'nip'                   => $nip,                // F: NIP
            'nidn'                  => null,                // NIDN tidak ada di Excel
            'tmt_cpns_ppk'          => $tmtCpnsPpk,         // G: TMT CPNS/PPPK
            'pendidikan'            => isset($row[7]) && trim($row[7]) != '' ? trim($row[7]) : null, // H: PENDIDIKAN
            'tahun_lulus'           => isset($row[8]) && trim($row[8]) != '' ? trim($row[8]) : null, // I: TAHUN LULUS
            'pangkat_id'            => $this->getPangkatId($row[9] ?? null), // J: PANGKAT
            'golongan_id'           => $this->getGolonganId($row[10] ?? null), // K: GOLONGAN
            'tmt'                   => $tmt,                // L: TMT
            'jf'                    => isset($row[12]) && trim($row[12]) != '' ? trim($row[12]) : null, // M: JF
            'tmt_jf'                => $tmtJf,              // N: TMT (TMT JF)
            'status_kepegawaian'    => isset($row[14]) && trim($row[14]) != '' ? trim($row[14]) : null, // O: STATUS (PNS/PPPK/HONORER)
            'tahun_purna_tugas'     => $tahunPurnaTugas,    // P: TAHUN PURNA TUGAS
            'nuptk'                 => $nuptk,              // Q: NUPTK
            'jurusan'               => isset($row[17]) && trim($row[17]) != '' ? trim($row[17]) : null, // R: JURUSAN
            'prodi_id'              => $this->getProdiId($row[18] ?? null), // S: PRODI
        ];

            // Update or create - jika NIP ada dan tidak kosong, check duplikat
            if ($nip) {
                $dosen = Dosen::updateOrCreate(
                    ['nip' => $nip],
                    $data
                );
                
                // Check if was recently created (new record) or updated
                if ($dosen->wasRecentlyCreated) {
                    $this->createdCount++;
                } else {
                    $this->updatedCount++;
                }
                return null;
            }

            // Jika tidak ada NIP atau NIP "-", tetap create baru
            $dosen = new Dosen($data);
            $dosen->save();
            $this->createdCount++;
            return null;
            
        } catch (\Exception $e) {
            // Log failed row dengan detail lebih lengkap
            $this->failedRows[] = [
                'nama' => $row[1] ?? 'Unknown',
                'nip' => $row[5] ?? '-',
                'jurusan' => $row[17] ?? '-',
                'error' => $e->getMessage()
            ];
            Log::error('Import Dosen Failed: ' . $e->getMessage(), [
                'row_data' => $data,
                'nama' => $row[1] ?? 'Unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function getCreatedCount()
    {
        return $this->createdCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getFailedRows()
    {
        return $this->failedRows;
    }

    /**
     * Start from row 2 (skip header)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($value)
    {
        if (empty($value) || trim($value) == '') {
            return null;
        }

        // Clean the value
        $value = trim($value);

        try {
            // If it's a numeric Excel date (serial number)
            if (is_numeric($value) && $value > 0 && $value < 100000) {
                try {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    return Carbon::instance($date);
                } catch (\Exception $e) {
                    return null;
                }
            }

            // Try parsing DD/MM/YYYY format
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
                $day = (int)$matches[1];
                $month = (int)$matches[2];
                $year = (int)$matches[3];
                
                // Validate date components
                if ($year > 1900 && $year < 2100 && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    try {
                        return Carbon::createFromFormat('d/m/Y', $value);
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            }

            // Try parsing YYYY-MM-DD format
            if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $matches)) {
                $year = (int)$matches[1];
                $month = (int)$matches[2];
                $day = (int)$matches[3];
                
                // Validate date components
                if ($year > 1900 && $year < 2100 && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    try {
                        return Carbon::createFromFormat('Y-m-d', $value);
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            }

            // Skip invalid dates
            return null;
        } catch (\Exception $e) {
            // Jika ada error apapun, return null
            return null;
        }
    }

    /**
     * Parse jenis kelamin
     */
    private function parseJenisKelamin($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = strtolower(trim($value));
        
        if (in_array($value, ['l', 'laki-laki', 'laki', 'male', 'm'])) {
            return 'Laki-laki';
        }
        
        if (in_array($value, ['p', 'perempuan', 'female', 'f'])) {
            return 'Perempuan';
        }

        return null;
    }

    /**
     * Get Prodi ID by name
     */
    private function getProdiId($nama)
    {
        if (empty($nama)) {
            return null;
        }

        $prodi = \App\Models\Prodi::where('nama', 'like', '%' . trim($nama) . '%')->first();
        return $prodi ? $prodi->id : null;
    }

    /**
     * Get Pangkat ID by name
     */
    private function getPangkatId($nama)
    {
        if (empty($nama)) {
            return null;
        }

        $pangkat = \App\Models\Pangkat::where('nama', 'like', '%' . trim($nama) . '%')->first();
        return $pangkat ? $pangkat->id : null;
    }

    /**
     * Get Golongan ID by name
     */
    private function getGolonganId($nama)
    {
        if (empty($nama)) {
            return null;
        }

        $golongan = \App\Models\Golongan::where('nama', 'like', '%' . trim($nama) . '%')->first();
        return $golongan ? $golongan->id : null;
    }
}

<?php

namespace App\Imports;

use App\Models\Teknisi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TeknisiImport implements ToModel, WithStartRow, SkipsOnError
{
    use SkipsErrors;

    private $createdCount = 0;
    private $updatedCount = 0;
    private $skippedCount = 0;
    private $failedRows = [];

    public function model(array $row)
    {
        // Skip empty rows or rows without nama
        if (empty($row[1]) || trim($row[1]) == '' || trim($row[1]) == '-') {
            $this->skippedCount++;
            return null;
        }

        // Skip rows that are completely empty (check multiple columns)
        $hasData = false;
        for ($i = 1; $i <= 15; $i++) {
            if (isset($row[$i]) && trim($row[$i]) != '' && trim($row[$i]) != '-') {
                $hasData = true;
                break;
            }
        }
        
        if (!$hasData) {
            $this->skippedCount++;
            return null;
        }

        try {
            // Parse jenis kelamin
            $jenisKelamin = null;
            if (isset($row[2]) && trim($row[2]) != '') {
                $jk = strtoupper(trim($row[2]));
                $jenisKelamin = ($jk == 'L') ? 'Laki-laki' : (($jk == 'P') ? 'Perempuan' : trim($row[2]));
            }

            // Clean NIP - convert "-" to null, dan batasi panjang maksimal 50 karakter
            $nip = null;
            if (isset($row[3]) && trim($row[3]) != '' && trim($row[3]) != '-') {
                $nipRaw = trim($row[3]);
                // Jika NIP terlalu panjang (kemungkinan salah format), skip atau potong
                if (strlen($nipRaw) > 50) {
                    throw new \Exception("NIP terlalu panjang (" . strlen($nipRaw) . " karakter). Maksimal 50 karakter.");
                }
                $nip = $nipRaw;
            }

            // Helper function untuk trim dan batasi panjang
            $cleanField = function($value, $maxLength = 255) {
                if (!isset($value) || trim($value) == '' || trim($value) == '-') {
                    return null;
                }
                $cleaned = trim($value);
                // Jika terlalu panjang, potong
                if (strlen($cleaned) > $maxLength) {
                    $cleaned = substr($cleaned, 0, $maxLength);
                }
                return $cleaned;
            };

            // Parse tanggal lahir
            $tanggalLahir = $this->parseDate($row[5]);
            
            // Parse TMT CPNS PPPK
            $tmtCpnsPpk = $this->parseDate($row[6]);
            
            // Parse TMT GOL
            $tmtGol = $this->parseDate($row[10]);
            
            // Parse Tahun Purna Tugas
            $tahunPurnaTugas = $this->parseDate($row[13]);

            // Konversi GOL ke Pangkat
            $gol = $cleanField($row[9], 50);
            $pangkat = $this->convertGolToPangkat($gol);

            // Prepare data berdasarkan struktur gambar
            $data = [
                'nama'                  => $cleanField($row[1]),    // B: Nama
                'jenis_kelamin'         => $jenisKelamin,    // C: Jenis Kelamin (L/P)
                'nip'                   => $nip,              // D: NIP
                'tempat_lahir'          => $cleanField($row[4]),    // E: Tempat Lahir
                'tanggal_lahir'         => $tanggalLahir,    // F: Tanggal Lahir
                'tmt_cpns_ppk'          => $tmtCpnsPpk,      // G: TMT CPNS PPPK
                'pendidikan_terakhir'   => $cleanField($row[7]),    // H: Pendidikan terakhir
                'tahun_lulus'           => $cleanField($row[8], 10), // I: Tahun lulus
                'gol'                   => $gol,             // J: GOL
                'tmt_gol'               => $tmtGol,          // K: TMT GOL
                'pangkat'               => $pangkat,         // L: Pangkat (dari konversi GOL)
                'kelas_jabatan'         => $cleanField($row[12]),   // M: Kelas Jabatan
                'tahun_purna_tugas'     => $tahunPurnaTugas, // N: TUN PURNA TUGAS
                'status_kepegawaian'    => $cleanField($row[14]),   // O: Status (PNS,PPPK,Honorer/THL)
                'unit_kerja'            => $cleanField($row[15]),   // P: Unit Kerja
            ];

            // Update or create - jika NIP ada dan tidak kosong, check duplikat
            if ($nip) {
                $teknisi = Teknisi::updateOrCreate(
                    ['nip' => $nip],
                    $data
                );
                
                // Check if was recently created (new record) or updated
                if ($teknisi->wasRecentlyCreated) {
                    $this->createdCount++;
                } else {
                    $this->updatedCount++;
                }
                return null;
            }

            // Jika tidak ada NIP atau NIP "-", tetap create baru
            $teknisi = new Teknisi($data);
            $teknisi->save();
            $this->createdCount++;
            return null;
            
        } catch (\Exception $e) {
            // Log failed row dengan detail lebih lengkap
            $this->failedRows[] = [
                'nama' => $row[1] ?? 'Unknown',
                'nip' => $row[3] ?? '-',
                'unit_kerja' => $row[15] ?? '-',
                'error' => $e->getMessage()
            ];
            Log::error('Import Teknisi Failed: ' . $e->getMessage(), [
                'row_data' => $data ?? [],
                'nama' => $row[1] ?? 'Unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function startRow(): int
    {
        return 2;
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

    private function parseDate($value)
    {
        if (empty($value) || trim($value) == '' || trim($value) == '-') {
            return null;
        }

        try {
            // Jika nilai adalah angka (Excel serial date)
            if (is_numeric($value)) {
                $date = Date::excelToDateTimeObject($value);
                
                // Validasi tahun (1900-2100)
                $year = (int)$date->format('Y');
                if ($year < 1900 || $year > 2100) {
                    return null;
                }
                
                return $date->format('Y-m-d');
            }

            // Jika format string tanggal
            $dateStr = trim($value);
            
            // Coba parse format DD/MM/YYYY atau DD-MM-YYYY
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateStr, $matches)) {
                $day = (int)$matches[1];
                $month = (int)$matches[2];
                $year = (int)$matches[3];
                
                // Validasi
                if ($year >= 1900 && $year <= 2100 && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    return sprintf('%04d-%02d-%02d', $year, $month, $day);
                }
                return null;
            }
            
            // Coba parse format YYYY-MM-DD
            if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $dateStr, $matches)) {
                $year = (int)$matches[1];
                $month = (int)$matches[2];
                $day = (int)$matches[3];
                
                // Validasi
                if ($year >= 1900 && $year <= 2100 && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    return sprintf('%04d-%02d-%02d', $year, $month, $day);
                }
                return null;
            }

            // Coba parse dengan Carbon sebagai fallback
            $carbonDate = Carbon::parse($dateStr);
            return $carbonDate->format('Y-m-d');
            
        } catch (\Exception $e) {
            // Jika gagal parse, return null
            return null;
        }
    }

    private function convertGolToPangkat($gol)
    {
        if (empty($gol) || trim($gol) == '' || trim($gol) == '-') {
            return null;
        }

        $golClean = strtoupper(trim($gol));
        
        // Mapping GOL ke Pangkat sesuai formula Excel
        $mapping = [
            'IVA' => 'Pembina',
            'IIID' => 'Penata Tk.I',
            'IIIC' => 'Penata',
            'IIIB' => 'Penata Muda Tk.I',
            'IIIA' => 'Penata Muda',
            'IID' => 'Pengatur Tk. I',
            'IIC' => 'Pengatur',
            'IIB' => 'Pengatur Muda Tk.I',
            'IIA' => 'Pengatur Muda',
        ];

        // Cek di mapping
        if (isset($mapping[$golClean])) {
            return $mapping[$golClean];
        }

        // Jika tidak ada di mapping, return null atau nilai aslinya
        return null;
    }
}

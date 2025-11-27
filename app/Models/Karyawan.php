<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nama',
        'foto',
        'jenis_kelamin',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'unit_kerja',
        'penempatan_kerja',
        'tmt_pns_p3k',
        'status_kepegawaian',
        'pendidikan_terakhir',
        'tahun_lulus',
        'pangkat',
        'gol',
        'tmt_gol',
        'kelas_jabatan',
        'tahun_purna_tugas',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
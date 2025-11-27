<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;

    protected $table = 'teknisi';

    protected $fillable = [
        'nama',
        'foto',
        'jenis_kelamin',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'tmt_cpns_ppk',
        'pendidikan_terakhir',
        'tahun_lulus',
        'gol',
        'tmt_gol',
        'pangkat',
        'kelas_jabatan',
        'tahun_purna_tugas',
        'status_kepegawaian',
        'unit_kerja',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
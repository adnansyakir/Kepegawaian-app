<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nama_dosen',
        'foto',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nip',
        'nidn',
        'tmt_cpns_ppk',
        'pendidikan',
        'tahun_lulus',
        'pangkat_id',
        'golongan_id',
        'tmt',
        'jf',
        'tmt_jf',
        'status_kepegawaian',
        'tahun_purna_tugas',
        'nuptk',
        'jurusan',
        'prodi_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt_cpns_ppk' => 'date',
        'tmt' => 'date',
        'tmt_jf' => 'date',
        'tahun_purna_tugas' => 'date',
    ];
}
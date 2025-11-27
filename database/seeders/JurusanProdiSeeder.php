<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Prodi;

class JurusanProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            [
                'nama' => 'Teknik Elektro',
                'prodis' => [
                    ['nama' => 'D3 Teknik Elektro'],
                    ['nama' => 'D4 Teknik Elektro'],
                    ['nama' => 'D4 Teknologi Pembangkit Tenaga Listrik'],
                ]
            ],
            [
                'nama' => 'Teknik Mesin',
                'prodis' => [
                    ['nama' => 'D3 Teknik Mesin'],
                    ['nama' => 'D4 Teknik Mesin'],
                    ['nama' => 'D4 Teknik Mesin Otomotif'],
                ]
            ],
            [
                'nama' => 'Teknik Sipil',
                'prodis' => [
                    ['nama' => 'D3 Teknik Sipil'],
                    ['nama' => 'D4 Teknik Sipil'],
                    ['nama' => 'D4 Teknik Konstruksi Bangunan'],
                ]
            ],
            [
                'nama' => 'Teknologi Informasi',
                'prodis' => [
                    ['nama' => 'D3 Teknologi Informasi'],
                    ['nama' => 'D4 Teknologi Informasi'],
                    ['nama' => 'D4 Teknologi Sistem Informasi'],
                ]
            ],
            [
                'nama' => 'Administrasi Bisnis',
                'prodis' => [
                    ['nama' => 'D3 Administrasi Bisnis'],
                    ['nama' => 'D4 Administrasi Bisnis'],
                    ['nama' => 'D4 Administrasi Bisnis Digital'],
                ]
            ],
            [
                'nama' => 'Akuntansi',
                'prodis' => [
                    ['nama' => 'D3 Akuntansi'],
                    ['nama' => 'D4 Akuntansi'],
                    ['nama' => 'D4 Akuntansi Manajerial'],
                ]
            ],
            [
                'nama' => 'Teknik Kimia',
                'prodis' => [
                    ['nama' => 'D3 Teknik Kimia'],
                    ['nama' => 'D4 Teknik Kimia'],
                    ['nama' => 'D4 Teknik Kimia Industri'],
                ]
            ],
            [
                'nama' => 'Teknik Lingkungan',
                'prodis' => [
                    ['nama' => 'D3 Teknik Lingkungan'],
                    ['nama' => 'D4 Teknik Lingkungan'],
                    ['nama' => 'D4 Teknologi Pengelolaan Lingkungan Air'],
                ]
            ],
        ];

        foreach ($jurusans as $jurusanData) {
            $jurusan = Jurusan::create([
                'nama' => $jurusanData['nama'],
            ]);

            foreach ($jurusanData['prodis'] as $prodiData) {
                Prodi::create([
                    'jurusan_id' => $jurusan->id,
                    'nama' => $prodiData['nama'],
                ]);
            }
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = [
        'nama'
    ];

    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }
}

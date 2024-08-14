<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas'
    ];

    public function dataSiswa()
    {
        return $this->hasMany(data_siswa::class, 'kelas_id', 'id');
    }
}


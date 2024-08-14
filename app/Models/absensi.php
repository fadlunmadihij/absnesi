<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['data_siswa_id', 'nama_kelas', 'tanggal', 'status'];

    public function dataSiswa()
    {
        return $this->belongsTo(data_siswa::class, 'data_siswa_id', 'id');
    }

    public function namaKelas()
{
    return $this->belongsTo(Kelas::class, 'nama_kelas', 'id');
}
}

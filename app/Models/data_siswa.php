<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_siswa extends Model
{
    use HasFactory;

    protected $table = 'data_siswas';

    protected $fillable = [
        'kelas_id',
        'nama',
        'alamat',
        'jenis_kelamin',
        'NISN',
        'No_wa'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function absen()
{
    return $this->hasMany(Absensi::class, 'data_siswa_id', 'id');
}

}


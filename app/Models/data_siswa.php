<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_siswa extends Model
{
    protected $table = 'data_siswas';
    protected $fillable = [
        'kelas_id',
        'nama',
        'alamat',
        'jenis_kelamin',
        'NISN',
        'No_wa'

    ];
    use HasFactory;
    public function kelas()
    {
        return $this->belongsTo(kelas::class);
    }
    public function absen()
    {
        return $this->hasMany(absensi::class);
    }
}

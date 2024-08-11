<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
    protected $fillable = [
        'nama_kelas'
    ];
    use HasFactory;
    public function dataSiswa()
    {
        return $this->hasMany(data_siswa::class);
    }
}

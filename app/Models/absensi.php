<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensi extends Model
{
    protected $fillable = ['data_siswa_id', 'tanggal', 'status'];
    use HasFactory;
    public function dataSiswa()
    {
        return $this->belongsTo(data_siswa::class);
    }

}

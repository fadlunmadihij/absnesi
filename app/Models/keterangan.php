<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class keterangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_siswa_id',
        'keterangan',
        'file'
    ];

    public function data_siswa()
    {
        return $this->belongsTo(data_siswa::class, 'data_siswa_id', 'id');
    }
}

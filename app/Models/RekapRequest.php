<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'start_date',
        'end_date',
        'status',
    ];
}
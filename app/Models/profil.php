<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profil extends Model
{
    protected $fillable = [
        'users_id',
        'nama',
        'alamt',
        'foto'
    ];
    use HasFactory;
}

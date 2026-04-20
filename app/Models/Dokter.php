<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'no_ktp',
        'no_hp',
        'alamat',
        'id_poli',
        'password',
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}
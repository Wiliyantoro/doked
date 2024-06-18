<?php

namespace App\Models;

use App\Models\Foto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan', 'rincian_kegiatan', 'tanggal_kegiatan'
    ];

    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }
}

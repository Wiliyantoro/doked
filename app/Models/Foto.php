<?php

namespace App\Models;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Foto extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id', 'nama_file'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}

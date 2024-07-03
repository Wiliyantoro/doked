<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMemutuskan extends Model
{
    use HasFactory;

    protected $table = 'sub_memutuskan'; // Pastikan nama tabel benar
    protected $fillable = ['memutuskan_id', 'content'];

    public function memutuskan()
    {
        return $this->belongsTo(Memutuskan::class);
    }
}

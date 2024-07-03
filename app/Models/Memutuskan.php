<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memutuskan extends Model
{
    use HasFactory;

    protected $table = 'memutuskan'; // Pastikan nama tabel benar
    protected $fillable = ['regulation_id', 'title', 'content'];

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }

    public function subMemutuskan()
    {
        return $this->hasMany(SubMemutuskan::class);
    }
}

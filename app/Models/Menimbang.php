<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menimbang extends Model
{
    use HasFactory;

    protected $table = 'menimbang';
    protected $fillable = ['regulation_id', 'content'];

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }
}

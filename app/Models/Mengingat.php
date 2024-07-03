<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mengingat extends Model
{
    use HasFactory;

    protected $table = 'mengingat';
    protected $fillable = ['regulation_id', 'content'];

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }
}

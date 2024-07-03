<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'regulation_number'];

    public function menimbang()
    {
        return $this->hasMany(Menimbang::class);
    }

    public function mengingat()
    {
        return $this->hasMany(Mengingat::class);
    }

    public function memutuskan()
    {
        return $this->hasMany(Memutuskan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($regulation) {
            $regulation->menimbang()->delete();
            $regulation->mengingat()->delete();
            $regulation->memutuskan()->each(function ($memutuskan) {
                $memutuskan->subMemutuskan()->delete();
                $memutuskan->delete();
            });
        });
    }
}

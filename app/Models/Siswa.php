<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = ['nis','nama','kelas','qr_code_path','qr_token'];

    protected static function booted()
    {
        static::creating(function ($siswa) {
            if (empty($siswa->qr_token)) {
                $siswa->qr_token = (string) Str::uuid();
            }
        });
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}

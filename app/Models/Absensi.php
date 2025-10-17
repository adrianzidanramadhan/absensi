<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['siswa_id', 'tanggal', 'waktu', 'status'];
    public $timestamps = true;


    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

}
